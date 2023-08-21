<?php
namespace App\Api\Services;

use App\Api\Repositories\Events\EventOrderEvaluateRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

use App\Api\Repositories\User\UsersRepository;
use App\Api\Repositories\User\UserDetailRepository;
use App\Api\Repositories\User\UserTagsRepository;
use App\Api\Repositories\Events\EventOrderRepository;
use App\Api\Repositories\Events\EventQaRepository;
use App\Api\Repositories\Events\EventsRepository;
use App\Api\Repositories\Idx\IdxSettingRepository;
use App\Api\Repositories\Log\LogFeedbackRepository;
use App\Api\Repositories\Log\LogSysMessageRepository;
use App\Api\Repositories\Log\LogUserFundRepository;
use App\Api\Repositories\Log\LogUserVipRepository;
use App\Api\Repositories\Log\LogWithdrawRepository;
use App\Api\Repositories\User\UserAlbumRepository;
use App\Api\Repositories\User\UserFundsRepository;

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
            'vip'=> $user->vip,
            'vip_expriation_time'=> $user->vip_expriation_time,
            'attention_number'=> (new UsersRepository())->get_attention_count($user->id),
            'fans_number'=> (new UsersRepository())->get_fans_count($user->id),
            'palyed_number'=> $is_me ? 0 : (new EventOrderRepository())->一起玩过次数($user_id, $other_id),
            'is_attention'=> $is_me ? false : (new UsersRepository())->get_attention_status($user_id, $other_id),
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

    /**
     * 清理已过期的VIP
     *
     * @return void
     */
    public function vip_check_operation(){
        (new UsersRepository())->清理已过期VIP();
    }

    /**
     * 重新来过
     *
     * @param integer $user_id
     * @return void
     */
    public function restart_operation(int $user_id){
        // 判断会员是否有报名中/进行中的活动，如果有则无法重新来过
        $event_count = (new EventsRepository())->获取会员正在进行的活动数量($user_id);
        if($event_count > 0){
            throwBusinessException("有正在报名/进行中的活动，无法执行当前操作");
        }
        if((new UsersRepository())->check_restart($user_id) == false){
            throwBusinessException("太过频繁了");
        }
        DB::beginTransaction();
        try{
            // 删除会员信用分、余额
            (new UserFundsRepository())->init_data($user_id);
            // 删除会员信息、VIP
            (new UsersRepository())->清理会员数据($user_id);
            // 删除会员记录：资产记录、VIP记录、提现记录
            (new LogUserVipRepository())->delete_user_data($user_id);
            (new LogUserFundRepository())->delete_user_data($user_id);
            (new LogWithdrawRepository())->delete_user_data($user_id);
            // 删除会员订单、订单评价
            (new EventOrderRepository())->delete_user_data($user_id);
            (new EventOrderEvaluateRepository())->delete_user_data($user_id);
            // 删除会员活动、参加我活动的订单、对我的评价
            (new EventsRepository())->delete_user_data($user_id);
            (new EventOrderRepository())->delete_publisher_data($user_id);
            (new EventOrderEvaluateRepository())->delete_publisher_data($user_id);
            // 删除对会员的通知
            (new LogSysMessageRepository())->delete_user_data($user_id);
            // 删除会员的标签、影集
            (new UserTagsRepository())->delete_user_data($user_id);
            (new UserAlbumRepository())->delete_user_data($user_id);
            // 删除我对他人的标签的点赞
            (new UserTagsRepository())->delete_user_like_data($user_id);
            // 删除意见反馈
            (new LogFeedbackRepository())->delete_user_data($user_id);
            // 删除活动问答
            (new EventQaRepository())->delete_user_data($user_id);
            DB::commit();
            // 设置重新来过冷却时间
            (new UsersRepository())->set_restart($user_id);
            // 退出登录
            (new UsersRepository())->delete_token($user_id);
        }catch(\Exception $e){
            DB::rollBack();
        }
        return true;
    }

    /**
     * 注销账号
     *
     * @param integer $user_id
     * @return void
     */
    public function write_off_operation(int $user_id){
        // 判断会员是否有报名中/进行中的活动，如果有则无法重新来过
        $event_count = (new EventsRepository())->获取会员正在进行的活动数量($user_id);
        if($event_count > 0){
            throwBusinessException("有正在报名/进行中的活动，无法执行当前操作");
        }
        // 删除用户
        (new UsersRepository())->delete_user($user_id);
        // 退出登录
        (new UsersRepository())->delete_token($user_id);
        return true;
    }
}