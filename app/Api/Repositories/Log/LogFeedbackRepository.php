<?php
namespace App\Api\Repositories\Log;

use App\Models\Log\LogFeedback as Model;

class LogFeedbackRepository{
    protected $eloquentClass = Model::class;

    public function create_data(int $user_id, string $order_no, string $title, string $content, string $images, string $video){
        return $this->eloquentClass::create([
            'user_id'=> $user_id,
            'order_no'=> $order_no,
            'title'=> $title,
            'content'=> $content,
            'images'=> $images,
            'video'=> $video,
        ]);
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
}