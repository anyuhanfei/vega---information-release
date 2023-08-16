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

class EventStatus30 implements ShouldQueue{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event_id;

    public function __construct(int $event_id){
        $this->event_id = $event_id;
    }

    public function handle(){
        $event = (new EventsRepository())->use_id_get_one_data($this->event_id);
        if($event && $event->status == 20){
            // 将活动的状态修改为进行中
            (new EventsRepository())->update_event_status($this->event_id, 30);
            // 发送通知
            (new LogSysMessageRepository())->send_message("活动开始通知", $event->user_id, '', '您举办的活动已开始!');
            // 获取活动的有效订单
            $orders = (new EventOrderRepository())->use_status_get_event_orders_data($this->event_id, [20]);
            // 将活动的订单状态修改为待评价
            (new EventOrderRepository())->use_event_id_update_status_30_to_40($this->event_id);
            // 发送通知
            $user_ids = [];
            foreach($orders as $order){
                $user_ids[] = $order->user_id;
            }
            if(count($user_ids) > 0){
                (new LogSysMessageRepository())->send_message("活动开始通知", implode(',', $user_ids), '', "您参与的活动已开始!");
            }
        }
    }
}
