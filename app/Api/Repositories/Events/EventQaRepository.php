<?php
namespace App\Api\Repositories\Events;

use App\Models\Event\EventQa as Model;

class EventQaRepository{
    protected $eloquentClass = Model::class;

    public function create_data(int $user_id, int $event_id, int $publisher_id, int $question_id, string $content){
        return $this->eloquentClass::create([
            'user_id'=> $user_id,
            'event_id'=> $event_id,
            'publisher_id'=> $publisher_id,
            'question_id'=> $question_id,
            'content'=> $content,
        ]);
    }

    /**
     * 通过id获取一条数据
     *
     * @param integer $id
     * @return void
     */
    public function use_id_get_one_data(int $id){
        return $this->eloquentClass::id($id)->first();
    }

    /**
     * 获取活动下的所有问题
     *
     * @param integer $event_id
     * @return void
     */
    public function use_eventid_get_questions(int $event_id){
        return $this->eloquentClass::with(['answer'])->eventId($event_id)->questionId(0)->get();
    }

}