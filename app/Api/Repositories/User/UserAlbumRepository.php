<?php
namespace App\Api\Repositories\User;

use App\Models\User\UserAlbum as Model;

class UserAlbumRepository{
    protected $eloquentClass = Model::class;

    /**
     * 创建影集
     *
     * @param integer $user_id
     * @param string $type
     * @param string $tag
     * @return void
     */
    public function create_data(int $user_id, string $image, string $video, string $title){
        return $this->eloquentClass::create([
            'user_id'=> $user_id,
            'image'=> $image,
            'video'=> $video,
            'title'=> $title,
        ]);
    }

    /**
     * 删除影集
     *
     * @param integer $id
     * @return void
     */
    public function del_data(int $id){
        return $this->eloquentClass::destroy($id);
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
     * 获取图片列表，video为空
     *
     * @param integer $user_id
     * @param integer $page
     * @param integer $limit
     * @return void
     */
    public function get_image_list(int $user_id, int $page, int $limit){
        return $this->eloquentClass::userId($user_id)->page($page, $limit)->video('')->select(['id', 'image'])->get();
    }

    /**
     * 获取视频列表，video不为空
     *
     * @param integer $user_id
     * @param integer $page
     * @param integer $limit
     * @return void
     */
    public function get_video_list(int $user_id, int $page, int $limit){
        return $this->eloquentClass::userId($user_id)->page($page, $limit)->where("video", '<>', '')->select(['id', 'image', 'video', 'title', 'created_at'])->get();
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