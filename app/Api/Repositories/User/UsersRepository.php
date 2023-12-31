<?php

namespace App\Api\Repositories\User;

use App\Api\Repositories\Sys\SysSettingRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Hash;

use App\Models\User\Users as Model;

use App\Api\Repositories\User\UserFundsRepository;
use App\Api\Repositories\User\UserDetailRepository;


class UsersRepository{
    protected $eloquentClass = Model::class;

    /**
     * 创建会员
     *
     * @param [type] $field_values
     * @return void
     */
    public function create_data($field_values, $encrypt_password = true){
        if($encrypt_password){
            $field_values['password'] = $this->set_password($field_values['password']);
        }
        $obj = $this->eloquentClass::create($field_values);
        (new UserFundsRepository())->create_data($obj->id);
        (new UserDetailRepository())->create_data($obj->id);
        return $obj;
    }

    /**
     * 使用手机号获取一条会员信息
     *
     * @param string $phone
     * @return void
     */
    public function use_phone_get_one_data(string $phone){
        return $this->eloquentClass::phone($phone)->first();
    }

    /**
     * 使用账号获取一条会员信息
     *
     * @param string $account
     * @return void
     */
    public function use_account_get_one_data(string $account){
        return $this->eloquentClass::phone($account)->first();
    }

    /**
     * 使用ID获取一条会员信息
     *
     * @param integer $id
     * @return void
     */
    public function use_id_get_one_data(int $id){
        return $this->eloquentClass::id($id)->first();
    }

    /**
     * 使用模型已设置的查询条件，查询不确定传入参数的一条会员信息
     *
     * @param array $fields
     * @return void
     */
    public function use_apply_get_one_data(array $fields){
        return $this->eloquentClass::apply($fields)->first();
    }

    /**
     * 使用第三方标识获取一个会员信息
     *
     * @param string $openid 第三方标识
     * @param string $third_party 第三方平台名称
     * @return void
     */
    public function use_openid_get_one_data(string $openid, string $third_party = '微信公众号'){
        return $this->eloquentClass::openid($openid)->third_party($third_party)->first();
    }

    /**
     * 使用会员ID获取第三方标识
     *
     * @param integer $id 会员ID
     * @return string
     */
    public function use_id_get_openid(int $id, string $third_party = '微信公众号'):string{
        $openid = Cache::remember("user:openid:{$third_party}:{$id}", 86400000, function() use($id, $third_party){
            return $this->eloquentClass::id($id)->third_party($third_party)->value("openid") ?? '';
        });
        if($openid == ''){
            Cache::forget("user:openid:{$third_party}:{$id}");
        }
        return $openid;
    }

    /**
     * 获取指定会员的全部信息
     *
     * @param integer $id
     * @return void
     */
    public function get_user_detail(int $id){
        return $this->eloquentClass::id($id)->with(['detail', 'funds'])->first();
    }

    /**
     * 修改指定会员的数据
     *
     * @param integer $id
     * @param array $datas
     * @return void
     */
    public function update_user_detail(int $id, array $datas){
        return $this->eloquentClass::id($id)->update($datas);
    }

    /**
     * 设置会员的token
     *
     * @param int $user_id 会员id
     * @return void
     */
    public function set_token($user_id){
        $this->delete_token($user_id);
        $user_token = md5(Hash::make(time()));
        Redis::set('user_token:' . $user_token, $user_id);
        Redis::set('user_token:' . $user_id, $user_token);
        return $user_token;
    }

    /**
     * 删除会员的token信息
     *
     * @param int $user_id 会员id
     * @return void
     */
    public function delete_token($user_id){
        $token = $this->use_id_get_token($user_id);
        Redis::delete('user_token:' . $token);
        Redis::delete('user_token:' . $user_id);
        return true;
    }

    /**
     * 通过token获取会员的id
     *
     * @param string $token token
     * @return void
     */
    public function use_token_get_id($token){
        $user_id = Redis::get('user_token:' . $token);
        return $user_id ?? 0;
    }

    /**
     * 通过会员的id获取token
     *
     * @param int $user_id 会员id
     * @return void
     */
    public function use_id_get_token($user_id){
        $token = Redis::get('user_token:' . $user_id);
        return $token ?? '';
    }

    /**
     * 生成密码
     *
     * @param string $password 密码原码
     * @return array 加密密码
     */
    public function set_password($password){
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * 验证密码
     *
     * @param Eloquent $user_obj 会员数据对象
     * @param string $password 密码原码
     * @return void
     */
    public function verify_password($user_obj, $password){
        return password_verify($password, $user_obj->password);
    }

    /**
     * 获取邀请二维码
     *
     * @param integer $user_id 会员id
     * @param string $url 邀请链接
     * @return string
     */
    public function get_invite_qrcode(int $user_id, string $url):string{
        $save_url = Redis::get("invite_url:{$user_id}");
        if($save_url && $save_url == $url){  // 判断链接是否一致
            $invite_qrcode = Redis::get("invite_qrcode:{$user_id}");
            if(!$invite_qrcode){  // 判断二维码是否存在
                $invite_qrcode = qrcode($url, $user_id);
                Redis::set("invite_qrcode:{$user_id}", $invite_qrcode);
            }
        }else{
            Redis::set("invite_url:{$user_id}", $url);
            $invite_qrcode = qrcode($url, $user_id);
            Redis::set("invite_qrcode:{$user_id}", $invite_qrcode);
        }
        return $invite_qrcode;
    }

    /**
     * 保存openid对应的session_key
     *
     * @param string $openid
     * @param string $session_key
     * @return void
     */
    public function save_session_key(string $openid, string $session_key){
        Redis::set($openid, $session_key);
    }

    /**
     * 获取openid对应的session_key
     *
     * @param string $openid
     * @return string
     */
    public function get_session_key(string $openid):string{
        return Redis::get($openid);
    }

    /**
     * 获取会员是否关注了另一个会员
     *
     * @param integer $user_id
     * @param integer $other_id
     * @return boolean
     */
    public function get_attention_status(int $user_id, int $other_id):bool{
        return boolval(Redis::sismember("at:{$user_id}", $other_id));
    }

    public function get_attention_list(int $user_id){
        return Redis::smembers("at:{$user_id}");
    }

    public function get_fans_list(int $user_id){
        return Redis::smembers("byat:{$user_id}");
    }

    public function get_attention_count(int $user_id):int{
        return Redis::scard("at:{$user_id}") ?? 0;
    }

    public function get_fans_count(int $user_id):int{
        return Redis::scard("byat:{$user_id}") ?? 0;
    }

    /**
     * 关注、取消关注操作
     *
     * @param integer $user_id
     * @param integer $other_id
     * @return void
     */
    public function set_attention(int $user_id, int $other_id):array{
        // 判断是否已关注
        $status = $this->get_attention_status($user_id, $other_id);
        if($status){
            // 已关注，点击取消关注
            Redis::srem("at:{$user_id}", $other_id);
            Redis::srem("byat:{$other_id}", $user_id);
            return ['status'=> false];
        }else{
            // 未关注，点击关注
            Redis::sadd("at:{$user_id}", $other_id);
            Redis::sadd("byat:{$other_id}", $user_id);
            return ['status'=> true];
        }
    }

    /**
     * 清空会员的关注、粉丝
     *
     * @param [type] $user_id
     * @return void
     */
    public function clear_attention($user_id){
        foreach($this->get_attention_list($user_id) as $other_id){
            // 获取我关注的所有人, 删除这些人的粉丝列表中我的id
            Redis::srem("byat:{$other_id}", $user_id);
        }
        foreach($this->get_fans_list($user_id) as $other_id){
            // 获取关注我的所有人，删除这些人关注列表中的我的id
            Redis::srem("at:{$other_id}", $user_id);
        }
        Redis::del("at:{$user_id}");
        Redis::del("byat:{$user_id}");
    }

    /**
     * 获取一批会员的基本信息，用于展示粉丝、关注等会员列表
     *
     * @param integer $user_ids
     * @return void
     */
    public function get_users_basic_data(array $user_ids){
        return $this->eloquentClass::id($user_ids)->select(['id', 'avatar', 'nickname', 'age', 'bio', 'sex'])->get();
    }

    /**
     * 整理会员格言
     *
     * @param [type] $users
     * @return void
     */
    public function settle_users_bio(&$users){
        foreach($users as &$user){
            if($user->bio == ''){
                $user->bio = "TA还很懒，没有留下什么...";
            }
        }
    }

    /**
     * 获取会员设置的经纬度信息
     *
     * @param integer $user_id
     * @return array
     */
    public function get_user_coordinate(int $user_id):array{
        $data = Redis::get("ulong&lat:{$user_id}") ?? '';
        if($data == ''){
            return ['longitude'=> 0, 'latitude'=> 0];
        }
        try{
            $data = comma_str_to_array($data);
            return ['longitude'=> floatval($data[0]), 'latitude'=> floatval($data[1])];
        }catch(\Throwable $th){
            return ['longitude'=> 0, 'latitude'=> 0];
        }
    }

    /**
     * 设置会员的经纬度信息
     *
     * @param integer $user_id
     * @param string|float $longitude
     * @param string|float $latitude
     * @return void
     */
    public function set_user_coordinate(int $user_id, string|float $longitude, string|float $latitude){
        return Redis::set("ulong&lat:{$user_id}", $longitude . "," . $latitude);
    }

    public function update_vip(int $user_id, string $vip_name, string $vip_day){
        $user = $this->use_id_get_one_data($user_id);
        if($user){
            $start_time = $user->vip_expriation_time == null ? time() : strtotime($user->vip_expriation_time);
            $expriation_time = date("Y-m-d H:i:s", $start_time + (86400 * $vip_day));
            $this->eloquentClass::id($user_id)->update([
                'vip'=> $vip_name,
                'vip_expriation_time'=> $expriation_time
            ]);
        }
        return ['start_time'=> date("Y-m-d H:i:s", $start_time), 'expriation_time'=> $expriation_time];
    }

    public function 清理已过期VIP(){
        $this->eloquentClass::whereDate("vip_expriation_time", '<',  date("Y-m-d H:i:s", time()))->whereTime("vip_expriation_time", '<', date("Y-m-d H:i:s", time()))->update([
            'vip'=> '',
            'vip_expriation_time'=> null
        ]);
    }

    /**
     * 删除会员
     *
     * @param integer $user_id
     * @return void
     */
    public function delete_user(int $user_id){
        $this->eloquentClass::where("id", $user_id)->delete();
    }

    public function 清理会员数据(int $user_id){
        return $this->eloquentClass::where("id", $user_id)->update([
            'bio'=> '',
            'vip'=> '',
            'vip_expriation_time'=> null,
        ]);
    }

    public function set_restart(int $user_id){
        return Redis::set("restart:{$user_id}", time());
    }

    /**
     * 判断是否可重新来过
     *
     * @param integer $user_id
     * @return void
     */
    public function check_restart(int $user_id){
        $上次重新来过时间 = Redis::get("restart:{$user_id}") ?? 0;
        $冷却时间 = (new SysSettingRepository())->use_id_get_value(34) * 86400;
        return ($上次重新来过时间 + $冷却时间) < time();
    }
}
