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
}

