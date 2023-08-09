<?php
namespace App\Api\Repositories\Sys;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;

use App\Models\Sys\SysNotice as Model;

class SysNoticeRepository{
    protected $eloquentClass = Model::class;

    public function use_id_get_one_data(int $id){
        return Cache::tags("notice:{$id}")->remember("notice:{$id}", 8640000, function() use($id){
            return $this->eloquentClass::id($id)->first();
        });
    }

    /**
     * 用于单条模式的情况
     */
    public function get_first_data(){
        return Cache::tags(["notice"])->remember("notice:first", 8640000, function(){
            return $this->eloquentClass::first();
        });
    }

    /**
     * 获取公告列表
     *
     * @param integer $page
     * @param integer $limit
     * @return void
     */
    public function get_list(int $page, int $limit){
        return Cache::tags(["notice"])->remember("notice:list", 8640000, function() use($page, $limit){
            return $this->eloquentClass::page($page, $limit)->orderBy('id', 'desc')->select(['id', 'title', 'image', 'created_at'])->get();
        });
    }

    /**
     * 获取当前公告是否已读
     *
     * @param int $user_id 会员id
     * @param int $id 公告id
     * @return bool
     */
    public function get_read_status(int $user_id, int $id):bool{
        return Redis::sismember('sys_notice:' . $user_id, $id);
    }

    /**
     * 将当前公告设置为已读
     *
     * @param int $user_id 会员id
     * @param int $id 公告id
     * @return bool
     */
    public function set_read_status(int $user_id, int $id):bool{
        return Redis::sadd('sys_notice:' . $user_id, $id);
    }

    /**
     * 删除缓存
     *
     * @param integer $id
     * @return void
     */
    public function del_cache(int $id = 0){
        Cache::tags("notice:{$id}")->flush();
        Cache::tags("notice")->flush();
    }
}