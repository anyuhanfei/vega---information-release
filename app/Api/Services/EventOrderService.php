<?php
namespace App\Api\Services;

use App\Api\Repositories\Events\EventOrderRepository;
use App\Api\Repositories\Events\EventsRepository;
use App\Api\Repositories\Sys\SysSettingRepository;
use App\Api\Repositories\User\UsersRepository;

class EventOrderService{

    public function create_order_operation(int $user_id, int $event_id, int $number, string $information, string $pay_method){
        $user = (new UsersRepository())->use_id_get_one_data($user_id);
        $event = (new EventsRepository())->use_id_get_one_data($event_id);
        if(!$event || $event->status < 20){
            throwBusinessException("该活动已下架");
        }
        if($event->status == 40){
            throwBusinessException("该活动已结束");
        }
        // 判断性别是否符合要求
        if($event->sex_limit != '全部' && $event->sex_limit != $user->sex){
            throwBusinessException("该活动仅限{$event->sex_limit}性");
        }
        //计算价格
        $unit_price = 0;
        switch($event->charge_type){
            case "男收费":
                if($user->sex == '男'){
                    $unit_price = (new SysSettingRepository())->use_id_get_value(31);
                }
                break;
            case "女收费":
                if($user->sex == '女'){
                    $unit_price = (new SysSettingRepository())->use_id_get_value(30);
                }
                break;
            case "收费":
                $unit_price = (new SysSettingRepository())->use_id_get_value(29);
                break;
        }
        $all_price = $unit_price * $number;
        // 创建数据
        $data = (new EventOrderRepository())->create_data($user_id, $event_id, $event->user_id, $number, $unit_price, $all_price, $information);
        // 支付
        // $pay_data = (new PayService())->pay($pay_method, $user_id, $all_price, $data->order_no, '活动报名', '活动报名');
        // 测试阶段直接支付成功
        $pay_data = (new PayService())->活动报名($data->order_no);
        return $pay_data;
    }
}