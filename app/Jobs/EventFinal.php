<?php

namespace App\Jobs;

use App\Api\Repositories\Events\EventOrderEvaluateRepository;
use App\Api\Repositories\Events\EventOrderRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Api\Repositories\Events\EventsRepository;
use App\Api\Repositories\Log\LogSysMessageRepository;
use App\Api\Repositories\Sys\SysSettingRepository;
use App\Api\Repositories\User\UserFundsRepository;

/**
 * 活动结算
 */
class EventFinal implements ShouldQueue{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event_id;

    public function __construct(int $event_id){
        $this->event_id = $event_id;
    }

    public function handle(){
        $event = (new EventsRepository())->use_id_get_one_data($this->event_id);
        if($event && $event->status == 40){
            // 将活动的所有未评价的订单添加默认好评5星
            $已评价订单 = (new EventOrderEvaluateRepository())->use_event_id_get_data($this->event_id);
            $未评价订单 = (new EventOrderRepository())->use_status_get_event_orders_data($this->event_id, [40]);
            $order_number = 0;
            $score_number = 0;
            foreach($未评价订单 as $order){
                // 添加评价记录
                (new EventOrderEvaluateRepository())->create_data($order->user_id, $order->order_no, $order->event_id, $order->publisher_id, 5, '');
                // 修改订单状态
                (new EventOrderRepository())->evaluate_order_operation($order->order_no);
                $order_number += 1;
                $score_number += 5;
            }
            foreach($已评价订单 as $order){
                $order_number += 1;
                $score_number += $order->score;
            }
            $总评分 = ($order_number > 0) ? intval(round(($score_number / $order_number))) : -1;
            switch($总评分){
                case 1:
                    $信用分 = (new SysSettingRepository())->use_id_get_value(37);
                    break;
                case 2:
                    $信用分 = (new SysSettingRepository())->use_id_get_value(38);
                    break;
                case 3:
                    $信用分 = (new SysSettingRepository())->use_id_get_value(39);
                    break;
                case 4:
                    $信用分 = (new SysSettingRepository())->use_id_get_value(40);
                    break;
                case 5:
                    $信用分 = (new SysSettingRepository())->use_id_get_value(41);
                    break;
                default:
                    $信用分 = 0;
                    break;
            }
            if($信用分 == 0){
                (new UserFundsRepository())->update_fund($event->user_id, "credit", $信用分, "活动完成结算", "本次活动平均评分为{$总评分}");
            }
        }
    }
}
