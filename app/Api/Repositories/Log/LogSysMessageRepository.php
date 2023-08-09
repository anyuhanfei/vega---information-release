<?php
namespace App\Api\Repositories\Log;

use Illuminate\Support\Facades\Redis;

use App\Models\Log\LogSysMessage as Model;

use App\Admin\Repositories\Log\LogSysMessage as AdminLogSysMessage;


/**
 * 系统消息的获取纯粹是从redis中获取
 */
class LogSysMessageRepository{
    protected $eloquentClass = Model::class;

    /**
     * 通过uid获取会员当前页的系统消息数据
     *
     * @param integer $user_id
     * @param integer $page
     * @param integer $limit
     * @return array
     */
    public function use_uid_get_datas_form_redis(int $user_id, int $page = 1, int $limit = 10):array{
        return (new AdminLogSysMessage())->use_uid_get_datas_form_redis($user_id, $page, $limit);
    }

    /**
     * 根据id获取系统消息数据
     *
     * @param integer $id
     * @param integer $user_id
     * @return array
     */
    public function use_id_get_data_form_redis(int $id, int $user_id = null):array{
        $data = (new AdminLogSysMessage())->use_id_get_data_form_redis($id);
        return $data;
    }

    /**
     * 获取当前消息是否已读
     *
     * @param int $user_id 会员id
     * @param int $id 消息id
     * @return bool
     */
    public function get_read_status($user_id, $id){
        return Redis::sismember('sys_message_read:' . $user_id, $id);
    }

    /**
     * 将当前消息设置为已读
     *
     * @param int $user_id 会员id
     * @param int $id 消息id
     * @return bool
     */
    public function set_read_status($user_id, $id){
        return Redis::sadd('sys_message_read:' . $user_id, $id);
    }

    /**
     * 发送消息
     * 保存到mysql的同时，还要保存到redis
     *
     * @param integer $user_ids 会员id，默认为0，表示向全体会员发送消息
     * @param string $title 标题
     * @param string $image 图片
     * @param string $message 详细说明
     * @return void
     */
    public function send_message(string $title, string $user_ids = '0', string $image = '', string $message = ''){
        $data = $this->eloquentClass::create([
            'uid'=> $user_ids,
            'title'=> $title,
            'image'=> $image,
            'message'=> $message
        ]);
        (new AdminLogSysMessage())->save_uid_to_redis($data->id, $user_ids);
        (new AdminLogSysMessage())->save_data_to_redis($data->id, $title, $image, $message);
    }
}