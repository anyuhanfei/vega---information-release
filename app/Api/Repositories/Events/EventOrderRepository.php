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

    /**
     * 使用订单编号获取一条数据
     *
     * @param string|integer $order_no
     * @return void
     */
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

    /**
     * 获取指定状态、指定活动的活动订单
     *
     * @param integer $event_id
     * @param array $status
     * @return void
     */
    public function use_status_get_event_orders_data(int $event_id, array $status){
        return $this->eloquentClass::with(['user'])->eventId($event_id)->status($status)->get();
    }

    /**
     * 获取会员完成订单数量
     *
     * @param integer $user_id
     * @return void
     */
    public function get_user_over_order_number(int $user_id){
        return $this->eloquentClass::userId($user_id)->status([40, 50])->count();
    }

    /**
     * 获取活动已报名的订单
     *
     * @param integer $event_id
     * @return void
     */
    public function get_event_users_data(int $event_id){
        return $this->eloquentClass::with(['user'])->eventId($event_id)->status([20, 30, 40, 50])->get();
    }

    public function 获取活动的报名数据(int $event_id){
        $orders = $this->get_event_users_data($event_id);
        $user_avatars = [];
        foreach($orders as $order){
            if(count($user_avatars) < 2){
                $user_avatars[] = $order->user->avatar;
            }
        }
        // 报名人数，部分会员头像
        return [count($orders), $user_avatars];
    }

    public function 集体取消订单(int $event_id){
        $orders = $this->eloquentClass::with(['user'])->eventId($event_id)->get();
        foreach($orders as $order){
            $this->cancel_order('', $order);
        }
    }

    public function use_event_id_update_status_20_to_30(int $event_id){
        return $this->eloquentClass::eventId($event_id)->status(20)->update([
            'status'=> 30,
        ]);
    }

    public function use_event_id_update_status_30_to_40(int $event_id){
        return $this->eloquentClass::eventId($event_id)->status(30)->update([
            'status'=> 40,
        ]);
    }

    /**
     * 取消订单操作
     *
     * @param string $order_no
     * @param [type] $order
     * @return void
     */
    public function cancel_order(string $order_no = '', $order = null){
        if($order == null){
            $order = $this->eloquentClass::orderNo($order_no)->first();
        }
        if($order){
            $this->eloquentClass::orderNo($order->order_no)->update([
                'status'=> -1,
            ]);
            if($order->pay_price > 0){
                // TODO::退款
            }
        }
    }

    /**
     * 获取会员已完成的订单
     *
     * @param integer $user_id
     * @param integer $page
     * @param integer $limit
     * @return void
     */
    public function get_user_over_orders(int $user_id, int $page = 1, int $limit = 10){
        return $this->eloquentClass::with(['event', 'publisher'])->userId($user_id)->page($page, $limit)->status([40, 50])->orderBy("created_at", 'desc')->get();
    }

    /**
     * 获取我自己参与的订单，可根据状态筛选
     *
     * @param integer $user_id
     * @param integer|array $status
     * @param integer $page
     * @param integer $limit
     * @return void
     */
    public function use_status_get_user_over_orders(int $user_id, array $status, int $page = 1, int $limit = 10){
        return $this->eloquentClass::with(['event', 'publisher'])->userId($user_id)->page($page, $limit)->status($status)->orderBy("created_at", 'desc')->get();
    }

    /**
     * 评价
     *
     * @param string $order_no
     * @return void
     */
    public function evaluate_order_operation(string $order_no){
        return $this->eloquentClass::orderNo($order_no)->update([
            'status'=> 50
        ]);
    }

}

