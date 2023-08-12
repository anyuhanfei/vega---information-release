<?php
namespace App\Api\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

use App\Api\Repositories\User\UsersRepository;
use App\Api\Repositories\User\UserDetailRepository;
use App\Api\Repositories\User\UserTagsRepository;
use App\Api\Repositories\Events\EventOrderRepository;
use App\Api\Repositories\Idx\IdxSettingRepository;

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
        // 标签信息
        $UserTagsRepository = new UserTagsRepository();
        $tags = $UserTagsRepository->get_user_all_tags($user->id);
        foreach($tags as &$tag){
            $tag->status = $UserTagsRepository->get_like_status($user_id, $tag->id);
            $tag->like = $UserTagsRepository->get_like_number($tag->id);
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
            'tags'=> $tags,
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

    /**
     * 设置会员经纬度
     *
     * @param integer $user_id
     * @param string|float $longitude
     * @param string|float $latitude
     * @return void
     */
    public function set_user_coordinate(int $user_id, string|float $longitude, string|float $latitude){
        (new UsersRepository())->set_user_coordinate($user_id, $longitude, $latitude);
        return true;
    }

    /**
     * 获取会员信用分信息
     *
     * @param integer $user_id
     * @return void
     */
    public function get_user_credit(int $user_id){
        $user = (new UsersRepository())->use_id_get_one_data($user_id);
        // 获取会员的信用等级
        $credit_level = (new IdxSettingRepository())->use_type_get_datas('credit_level');
        $level_name = '';
        foreach($credit_level as $v){
            if($user->funds->credit >= $v->minimum && $user->funds->credit <= $v->maximum){
                $level_name = $v->name;
            }
        }
        return [
            'user_id'=> $user->id,
            'avatar'=> $user->avatar,
            'nickname'=> $user->nickname,
            'sex'=> $user->sex,
            'age'=> $user->age,
            'credit'=> $user->funds->credit,
            'credit_level'=> $level_name,
            'order_number'=> (new EventOrderRepository())->get_user_over_order_number($user_id),
        ];
    }
}