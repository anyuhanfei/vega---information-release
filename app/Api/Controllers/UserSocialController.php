<?php
namespace App\Api\Controllers;

use Illuminate\Http\Request;

use App\Api\Controllers\BaseController;

use App\Api\Services\UserSocialService;


class UserSocialController extends BaseController{
    protected $service;

    public function __construct(Request $request, UserSocialService $UserSocialService){
        parent::__construct($request);
        $this->service = $UserSocialService;
    }

    /**
     * 标签设置
     *
     * @return void
     */
    public function tag_set(Request $request){
        $tags = $request->input("tags");
        $res = $this->service->set_tags_operation($this->user_id, $tags);
        return success("设置成功");
    }

    /**
     * 点赞操作
     *
     * @param Request $request
     * @return void
     */
    public function tag_like(Request $request){
        $tag_id = $request->input("tag_id");
        $data = $this->service->tag_like_operation($this->user_id, $tag_id);
        return success("点赞成功", $data);
    }

    /**
     * 关注操作
     *
     * @param Request $request
     * @return void
     */
    public function attention(Request $request){
        $other_id = $request->input("other_id" ?? 0) ?? 0;
        $data = $this->service->attention_operation($this->user_id, $other_id);
        return success("操作成功", $data);
    }

    /**
     * 关注列表
     *
     * @param Request $request
     * @return void
     */
    public function attention_list(Request $request){
        $other_id = $request->input("other_id" ?? 0) ?? 0;
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        $data = $this->service->get_attention_list($this->user_id, $other_id, $page, $limit);
        return success("关注列表", $data);
    }

    /**
     * 获取粉丝列表
     *
     * @param \App\Api\Requests\PageRequest $request
     * @return void
     */
    public function fans_list(\App\Api\Requests\PageRequest $request){
        $other_id = $request->input("other_id" ?? 0) ?? 0;
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        $data = $this->service->get_fans_list($this->user_id, $other_id, $page, $limit);
        return success("粉丝列表", $data);
    }


    /**
     * 添加问答
     *
     * @param \App\Api\Requests\User\QASetRequest $request
     * @return void
     */
    public function qa_add(\App\Api\Requests\User\QASetRequest $request){
        $event_id = $request->input("event_id");
        $question_id = $request->input('question_id', 0) ?? 0;
        $content = $request->input('content');
        $res = $this->service->add_qa_operation($this->user_id, $event_id, $content, $question_id);
        return success("添加成功");
    }

    /**
     * 问答列表
     *
     * @param Request $request
     * @return void
     */
    public function qa_list(Request $request){
        $event_id = $request->input("event_id");
        $data = $this->service->get_qa_list($this->user_id, $event_id);
        return success("问答列表", $data);
    }

    public function album_add(\App\Api\Requests\User\AlbumAddRequest $request){
        $type = $request->input('type');
        $image = $request->input('image');
        $video = $request->input("video", '') ?? '';
        $title = $request->input("title", '') ?? '';
        $res = $this->service->add_album_operation($this->user_id, $image, $type == '视频' ? $video : '', $type == '视频' ? $title : '');
        return success("添加成功");
    }

    public function album_del(\App\Api\Requests\User\AlbumDelRequest $request){
        $album_id = $request->input("album_id");
        $res = $this->service->del_album_operation($album_id);
        return success("删除成功");
    }

    public function album_list(\App\Api\Requests\User\AlbumListRequest $request){
        $type = $request->input('type');
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        $data = $this->service->get_album_list($this->user_id, $type, $page, $limit);
        return success("影集", $data);
    }
}
