<?php
namespace App\Api\Repositories\Events;

use App\Models\Event\EventOrderEvaluates as Model;

/**
 * 活动订单评价
 */
class EventOrderEvaluateRepository{
    protected $eloquentClass = Model::class;

    public function create_data(int $user_id, string $order_no, int $event_id, int $publisher_id, int $score, string $tags){
        return $this->eloquentClass::create([
            'user_id' => $user_id,
            'order_no' => $order_no,
            'event_id' => $event_id,
            'publisher_id' => $publisher_id,
            'score' => $score,
            'tags' => $tags,
        ]);
    }

    public function use_event_id_get_data(int $event_id){
        return $this->eloquentClass::eventId($event_id)->get();
    }

    /**
     * 删除指定会员的日志
     *
     * @param integer $user_id
     * @return void
     */
    public function delete_user_data(int $user_id){
        return $this->eloquentClass::userId($user_id)->delete();
    }

    /**
     * 删除指定发布者的日志
     *
     * @param integer $user_id
     * @return void
     */
    public function delete_publisher_data(int $user_id){
        return $this->eloquentClass::publisherId($user_id)->delete();
    }
}


