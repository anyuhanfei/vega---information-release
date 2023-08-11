<?php
namespace App\Api\Services;

use App\Api\Repositories\User\UsersRepository;
use App\Api\Repositories\User\UserTagsRepository;


class UserSocialService{

    /**
     * 设置会员的标签
     *
     * @param integer $user_id
     * @param string $type
     * @param string $tag
     * @return void
     */
    public function set_tags_operation(int $user_id, string $type, string $tag){
        (new UserTagsRepository())->create_data($user_id, $type, $tag);
        return true;
    }

    /**
     * 点赞、取消点赞操作
     *
     * @param [type] $user_id
     * @param [type] $tag_id
     * @return array
     */
    public function tag_like_operation(int $user_id, int $tag_id):array{
        $res_data = (new UserTagsRepository())->set_like_status($user_id, $tag_id);
        return $res_data;
    }

    /**
     * 关注、取消关注操作
     *
     * @param integer $user_id
     * @param integer $other_id
     * @return void
     */
    public function attention_operation(int $user_id, int $other_id){
        $res_data = (new UsersRepository())->set_attention($user_id, $other_id);
        return $res_data;
    }

    /**
     * 获取关注列表
     *
     * @param integer $user_id
     * @param integer $other_id
     * @param integer $page
     * @param integer $limit
     * @return void
     */
    public function get_attention_list(int $user_id, int $other_id, int $page, int $limit){
        if($other_id == null || $other_id == '' || $other_id == 0 || $other_id == $user_id){
            // 获取自己的关注
            $attention_user_ids = (new UsersRepository())->get_attention_list($user_id);
        }else{
            // 获取他人的关注
            $attention_user_ids = (new UsersRepository())->get_attention_list($other_id);
        }
        $attention_user_ids = array_chunk($attention_user_ids, $limit);
        try{
            $users = (new UsersRepository())->get_users_basic_data($attention_user_ids[$page - 1]);
        }catch(\Throwable $th){
            $users = [];
        }
        (new UsersRepository())->settle_users_bio($users);
        return $users;
    }

    /**
     * 获取粉丝列表
     *
     * @param integer $user_id
     * @param integer $other_id
     * @param integer $page
     * @param integer $limit
     * @return void
     */
    public function get_fans_list(int $user_id, int $other_id, int $page, int $limit){
        if($other_id == null || $other_id == '' || $other_id == 0 || $other_id == $user_id){
            // 获取自己的粉丝
            $fans_user_ids = (new UsersRepository())->get_fans_list($user_id);
        }else{
            // 获取他人的粉丝
            $fans_user_ids = (new UsersRepository())->get_fans_list($other_id);
        }
        $fans_user_ids = array_chunk($fans_user_ids, $limit);
        try{
            $users = (new UsersRepository())->get_users_basic_data($fans_user_ids[$page - 1]);
        }catch(\Throwable $th){
            $users = [];
        }
        (new UsersRepository())->settle_users_bio($users);
        return $users;
    }
}