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
     * @param int $other_id 他人id
     * @return json
     */
    public function get_user_detail(int $user_id, int $other_id = 0){
        if($other_id == 0 || $other_id == $user_id){
            $is_me = true;
            $user = (new UsersRepository())->get_user_detail($user_id);
        }else{
            $is_me = false;
            $user = (new UsersRepository())->get_user_detail($other_id);
        }
        return [
            'id'=> $user->id,
            'phone'=> $user->phone,
            'avatar'=> $user->avatar,
            'nickname'=> $user->nickname,
            'sex'=> $user->sex,
            'identity'=> $user->identity,
            'bio'=> $user->bio,
            'age'=> $user->age,
            'shop_name'=> $user->detail->shop_name ?? '',
            'shop_year'=> $user->detail->shop_year ?? '',
            'shop_business'=> $user->detail->shop_business ?? '',
            'background'=> $user->detail->background,
            'background_type'=> $user->detail->background_type,
            'funds'=> [
                'money'=> $is_me ? $user->funds->money : 0,
                'credit'=> $user->funds->credit,
            ],
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
}