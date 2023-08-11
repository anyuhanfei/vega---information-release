<?php
namespace App\Api\Repositories\User;

use App\Models\User\UserTags as Model;
use Illuminate\Support\Facades\Redis;

class UserTagsRepository{
    protected $eloquentClass = Model::class;

    /**
     * 创建标签
     *
     * @param integer $user_id
     * @param string $type
     * @param string $tag
     * @return void
     */
    public function create_data(int $user_id, string $type, string $tag){
        return $this->eloquentClass::create([
            'user_id'=> $user_id,
            'type'=> $type,
            'tag'=> $tag
        ]);
    }

    /**
     * 获取会员全部的标签
     *
     * @param integer $user_id
     * @return void
     */
    public function get_user_all_tags(int $user_id){
        return $this->eloquentClass::userId($user_id)->select(['id', 'user_id', 'tag', 'type'])->get();
    }

    /**
     * 通过标签获取自己设置的一条数据
     *
     * @param integer $user_id
     * @param string $tag
     * @return void
     */
    public function use_tag_get_user_set_one_data(int $user_id, string $tag){
        return $this->eloquentClass::userId($user_id)->tag($tag)->type(['选自系统', '自定义'])->first();
    }

    /**
     * 获取当前点赞状态
     *
     * @param integer $user_id
     * @param integer $tag_id
     * @return void
     */
    public function get_like_status(int $user_id, int $tag_id){
        return boolval(Redis::get("tk:{$user_id}:{$tag_id}") ?? 0);
    }

    public function get_like_number(int $tag_id){
        return intval(Redis::get("tkn:{$tag_id}") ?? '0');
    }

    /**
     * 点赞
     *
     * @param integer $user_id
     * @param integer $tag_id
     * @return array
     */
    public function set_like_status(int $user_id, int $tag_id):array{
        $status = $this->get_like_status($user_id, $tag_id);
        if($status){
            // 已点赞，要取消点赞
            Redis::set("tk:{$user_id}:{$tag_id}", 0);
            $number = Redis::decr("tkn:{$tag_id}");
            if($number < 0){
                Redis::set("tkn:{$tag_id}", 0);
            }
        }else{
            // 未点赞，要点赞
            Redis::set("tk:{$user_id}:{$tag_id}", 1);
            $number = Redis::incr("tkn:{$tag_id}");
        }
        return ['status'=> !$status, 'number'=> $number];
    }
}