<?php

namespace App\Jobs;

use App\Api\Repositories\Events\EventOrderRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Api\Repositories\Events\EventsRepository;
use App\Api\Repositories\Log\LogSysMessageRepository;
use App\Api\Repositories\User\UserFundsRepository;

class EventStatus40 implements ShouldQueue{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event_id;

    public function __construct(int $event_id){
        $this->event_id = $event_id;
    }

    public function handle(){
        $event = (new EventsRepository())->use_id_get_one_data($this->event_id);
        if($event && $event->status == 30){
            // 将活动的状态修改为已完成
            (new EventsRepository())->update_event_status($this->event_id, 40);
            // 发送通知
            (new LogSysMessageRepository())->send_message("活动结束通知", $event->user_id, '', '您举办的活动已结束!');
            // 获取进行中的活动
            $orders = (new EventOrderRepository())->use_status_get_event_orders_data($this->event_id, [30]);
            // 将活动的订单状态修改为进行中
            (new EventOrderRepository())->use_event_id_update_status_30_to_40($this->event_id);
            // 发送通知
            $user_ids = [];
            $pay_money = 0;
            foreach($orders as $order){
                $user_ids[] = $order->user_id;
                $pay_money += $order->pay_price;
            }
            if(count($user_ids) > 0){
                (new LogSysMessageRepository())->send_message("活动结束通知", implode(',', $user_ids), '', "您参与的活动已结束，您可以进入订单列表对活动进行评价!");
            }
            // 将活动订单支付的金额添加到举办者余额
            (new UserFundsRepository())->update_fund($event->user_id, 'money', $pay_money, '活动结束', '活动结束结算报名费');
        }
    }
}
