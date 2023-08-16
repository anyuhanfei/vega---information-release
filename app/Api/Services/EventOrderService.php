<?php
namespace App\Api\Services;

use App\Api\Repositories\Events\EventOrderRepository;
use App\Api\Repositories\Events\EventsRepository;
use App\Api\Repositories\Log\LogSysMessageRepository;
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

    /**
     * 获取活动的全部订单
     *
     * @param integer $user_id
     * @param integer $event_id
     * @param string $type
     * @return void
     */
    public function get_event_orders(int $user_id, int $event_id, string $type){
        if($type == '待审核'){
            $list = (new EventOrderRepository())->use_status_get_event_orders_data($event_id, [10]);
        }else{
            $list = (new EventOrderRepository())->use_status_get_event_orders_data($event_id, [20, 30, 40, 50]);
        }
        $data = [];
        foreach($list as $v){
            $data[] = [
                'order_no'=> $v->order_no,
                'user_id'=> $v->user_id,
                'user_nickname'=> $v->user->nickname,
                'user_avatar'=> $v->user->avatar,
                'status'=> $v->status == 10 ? '待审核' : "已通过",
            ];
        }
        return $data;
    }

    /**
     * 审核订单操作
     */
    public function audit_order_operation(int $user_id, string $order_no, string $status){
        $order = (new EventOrderRepository())->use_order_no_get_one_data($order_no);
        if(!$order || $order->publisher_id != $user_id){
            throwBusinessException("无权限操作此订单");
        }
        if($order->status != 10){
            throwBusinessException("订单已审核");
        }
        if($status == '拒绝'){
            (new EventOrderRepository())->use_order_no_update_status($order_no, 19);
            //TODO：：退款

            // 通知会员订单拒绝
            (new LogSysMessageRepository())->send_message("订单申请已拒绝", $order->user_id, '', '您的参加活动申请已被拒绝，报名费用已全额退款');
        }else{
            (new EventOrderRepository())->use_order_no_update_status($order_no, 20);
            // 通知会员订单通过
            (new LogSysMessageRepository())->send_message("订单申请已通过", $order->user_id, '', "您的参加活动申请已通过，请按时参加活动");
        }
        return true;
    }

    /**
     * 获取他人订单列表
     *
     * @param integer $other_id
     * @param integer $page
     * @param integer $limit
     * @return void
     */
    public function get_other_orders(int $other_id, int $page, int $limit){
        $list = (new EventOrderRepository())->get_user_over_orders($other_id, $page, $limit);
        $data = [];
        foreach($list as $v){
            [$user_number, $user_avatars] = (new EventOrderRepository())->获取活动的报名数据($v->id);
            $data[] = [
                'event_id'=> $v->event_id,
                'event_title'=> $v->event->title,
                'event_address'=> $v->event->site_address,
                'event_image'=> $v->event->image,
                'publisher_id'=> $v->publisher_id,
                'publisher_nickname'=> $v->publisher->nickname,
                'publisher_avatar'=> $v->publisher->avatar,
                'event_start_time'=> date("m月d日", strtotime($v->event->start_time)),
                'user_number'=> $user_number,
                'user_avatars'=> $user_avatars,
            ];
        }
        return $data;
    }

    public function get_user_orders(int $user_id, string $status, int $page, int $limit){
        $status = [
            '全部'=> [-1, 0, 10, 19, 20, 30, 40, 50],
            '报名中'=> [20],
            '审核中'=> [10],
            '进行中'=> [30],
            '待评价'=> [40],
            '已完成'=> [50],
        ][$status];
        $list = (new EventOrderRepository())->use_status_get_user_over_orders($user_id, $status, $page, $limit);
        $coordinate = (new UsersRepository())->get_user_coordinate($user_id);
        $data = [];
        foreach($list as $v){
            [$user_number, $user_avatars] = (new EventOrderRepository())->获取活动的报名数据($v->id);
            $data[] = [
                'order_no'=> $v->order_no,
                'distance'=> get_distance($coordinate['longitude'], $coordinate['latitude'], $v->site_longitude, $v->site_latitude),
                'time'=> (new EventsRepository())->整理时间数据($v->event->start_time, $v->event->end_time),
                'publisher_avatar'=> $v->publisher->avatar,
                'publisher_nickname'=> $v->publisher->nickname,
                'event_image'=> $v->event->image,
                'event_title'=> $v->event->title,
                'status'=> $v->status,
                'user_number'=> $user_number,
                'user_avatars'=> $user_avatars,
            ];
        }
        return $data;
    }
}