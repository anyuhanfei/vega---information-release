<?php
namespace App\Api\Services;


use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

use App\Api\Repositories\User\UsersRepository;
use App\Api\Repositories\User\UserDetailRepository;
use Illuminate\Support\Facades\DB;

class UserService{
    /**
     * 通过token获取到会员id
     *
     * @param string $token token
     * @return int
     */
    public function use_token_get_id(string $token):int{
        return (new UsersRepository())->use_token_get_id($token);
    }

    /**
     * 获取会员详情
     *
     * @param int $user_id 会员id
     * @return json
     */
    public function get_user_detail(int $user_id){
        $data = (new UsersRepository())->get_user_detail($user_id);
        return [
            'id'=> $data->id,
            'phone'=> $data->phone,
            'avatar'=> $data->avatar,
            'nickname'=> $data->nickname,
            'detail'=> [
                'id_card_username'=> $data->detail->id_card_username,
            ],
            'funds'=> [
                'money'=> $data->funds->money,
            ]
        ];
    }

    /**
     * 修改会员数据（指定字段）
     *
     * @param int $user_id 会员id
     * @param array $datas
     * @return bool
     */
    public function update_datas(int $user_id, array $datas = []){
        // 特殊字段处理
        if(!empty($datas['password'])){
            $datas['password'] = (new UsersRepository())->set_password($datas['password']);
        }
        $data = (new UsersRepository())->get_user_detail($user_id);
        DB::beginTransaction();
        try{
            // 筛选出会员表的数据并修改
            $update_data = [];
            foreach($datas as $key=> $value){
                if($data->$key !== null){
                    $update_data[$key] = $datas[$key];
                }
            }
            if(count($update_data) >= 1){
                $res = (new UsersRepository())->update_user_detail($user_id, $update_data);
            }
            // 筛选出会员详情表的数据并修改
            $update_data = [];
            foreach($datas as $key=> $value){
                if($data->detail->$key !== null){
                    $update_data[$key] = $datas[$key];
                }
            }
            if(count($update_data) >= 1){
                $res = (new UserDetailRepository())->update_user_detail($user_id, $update_data);
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            throwBusinessException($e->getMessage());
        }
        return true;
    }

    /**
     * 获取微信绑定的手机号并保存手机号
     *
     * @param integer $user_id
     * @param string $iv
     * @param string $encryptedData
     * @return void
     */
    public function bind_wx_phone(int $user_id, string $iv, string $encryptedData){
        $user_data = (new UsersRepository())->get_user_detail($user_id);
        $phone = (new \App\Api\Tools\Wx\WxminiLoginTool())->get_wx_phone($user_data->openid, $iv, $encryptedData);
        $res = $this->update_datas($user_id, ['phone'=> $phone]);
        return $phone;
    }
}