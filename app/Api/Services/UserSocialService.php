<?php
namespace App\Api\Services;

use App\Api\Repositories\Events\EventsRepository;
use App\Api\Repositories\User\UsersRepository;
use App\Api\Repositories\User\UserTagsRepository;
use App\Api\Repositories\Events\EventQaRepository;
use App\Api\Repositories\Idx\IdxSettingRepository;

class UserSocialService{

    /**
     * 设置会员的标签
     *
     * @param integer $user_id
     * @param string $type
     * @param string $tag
     * @return void
     */
    public function set_tags_operation(int $user_id, string $tags){
        $tags = comma_str_to_array($tags);
        if(count($tags) > 0){
            $user_tags = (new UserTagsRepository())->get_user_all_tags($user_id);
            // $sys_tags = (new IdxSettingRepository())->get
            foreach($tags as $tag){
                $res = false;
                foreach($user_tags as $user_tag){
                    if($user_tag->type == '选自系统' || $user_tag->type == '自定义'){
                        if($tag == $user_tag->tag){
                            // 说明此标签已设置
                            $res = true;
                        }
                    }
                }
                if($res == false){
                    // 标签未添加，需要添加
                    $sys_tag = (new IdxSettingRepository())->use_tag_get_one_data($tag);
                    (new UserTagsRepository())->create_data($user_id, $sys_tag ? '选自系统' : "自定义", $tag);
                }
            }
        }
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

    /**
     * 添加疑问/回答问题
     *
     * @param integer $user_id
     * @param integer $event_id
     * @param string $content
     * @param integer $question_id
     * @return void
     */
    public function add_qa_operation(int $user_id, int $event_id, string $content, int $question_id = 0){
        $event = (new EventsRepository())->use_id_get_one_data($event_id);
        (new EventQaRepository())->create_data($user_id, $event_id, $event->user_id, $question_id, $content);
        return true;
    }

    /**
     * 获取问答列表
     *
     * @param integer $user_id
     * @param integer $event_id
     * @return void
     */
    public function get_qa_list(int $user_id, int $event_id){
        $data = (new EventQaRepository())->use_eventid_get_questions($event_id);
        $res = [];
        foreach($data as $v){
            $temp = [
                'question_id'=> $v->id,
                'user_id'=> $v->user_id,
                'content'=> $v->content,
                'answer'=> [],
                'answer_number'=> 0,
                'answer_user_avatars'=> [],
            ];
            foreach($v->answer as $a){
                $temp['answer'][] = [
                    'id'=> $a->id,
                    'user_id'=> $a->user_id,
                    'user_nickname'=> $a->user->nickname,
                    'user_avatar'=> $a->user->avatar,
                    'content'=> $a->content,
                ];
                $temp['answer_number'] += 1;
                if($temp['answer_number'] <= 2){
                    $temp['answer_user_avatars'][] = $a->user->avatar;
                }
            }
            $res[] = $temp;
        }
        return $res;
    }

    public function add_album_operation(int $user_id, string $image, string $video, string $title){
        $res = (new \App\Api\Repositories\User\UserAlbumRepository())->create_data($user_id, $image, $video, $title);
        return true;
    }

    public function del_album_operation(int $album_id){
        $res = (new \App\Api\Repositories\User\UserAlbumRepository())->del_data($album_id);
        return true;
    }

    public function get_album_list(int $user_id, string $type, int $page, int $limit){
        if($type == "图片"){
            $data = (new \App\Api\Repositories\User\UserAlbumRepository())->get_image_list($user_id, $page, $limit);
        }else{
            $data = (new \App\Api\Repositories\User\UserAlbumRepository())->get_video_list($user_id, $page, $limit);
            foreach($data as &$v){
                $v->created_at = date("Y.m.d", strtotime($v->created_at));
            }
        }
        return $data;
    }
}