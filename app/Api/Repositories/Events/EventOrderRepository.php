<?php
namespace App\Api\Repositories\Events;

use App\Models\Event\EventOrders as Model;

/**
 * 活动订单
 */
class EventOrderRepository{
    protected $eloquentClass = Model::class;

    public function create_data(int $user_id, int $event_id, int $publisher_id, int $number, int|float $unit_price, int|float $all_price, string $information_of_registration_value){
        $order_no = date("YmdH") . create_captcha(6);
        return $this->eloquentClass::create([
            'order_no'=> $order_no,
            'user_id'=> $user_id,
            'event_id'=> $event_id,
            'publisher_id'=> $publisher_id,
            'number'=> $number,
            'unit_price'=> $unit_price,
            'all_price'=> $all_price,
            'information_of_registration_value'=> $information_of_registration_value,
            'status'=> 0
        ]);
    }

    public function use_order_no_get_one_data(string|int $order_no){
        return $this->eloquentClass::orderNo($order_no)->first();
    }

    /**
     * 通过订单编号修改状态
     *
     * @param string|integer $order_no
     * @param integer $status
     * @return void
     */
    public function use_order_no_update_status(string|int $order_no, int $status){
        return $this->eloquentClass::orderNo($order_no)->update([
            'status'=> $status,
        ]);
    }
}