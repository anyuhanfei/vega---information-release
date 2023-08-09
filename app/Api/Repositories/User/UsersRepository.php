<?php

namespace App\Api\Repositories\User;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Hash;

use App\Models\User\Users as Model;
use App\Models\User\UserFunds;
use App\Models\User\UserDetail;


class UsersRepository{
    protected $eloquentClass = Model::class;

    /**
     * 创建会员
     *
     * @param [type] $field_values
     * @return void
     */
    public function create_data($field_values){
        $field_values['password'] = $this->set_password($field_values['password']);
        $obj = $this->eloquentClass::create($field_values);
        UserFunds::create(['id'=> $obj->id]);
        UserDetail::create(['id'=> $obj->id]);
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
}
