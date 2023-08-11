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
     * @param \App\Api\Requests\User\UserTagsSetRequest $request
     * @return void
     */
    public function tag_set(\App\Api\Requests\User\UserTagsSetRequest $request){
        $type = $request->input("type");
        $tag = $request->input("tag");
        $res = $this->service->set_tags_operation($this->user_id, $type, $tag);
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

    public function fans_list(\App\Api\Requests\PageRequest $request){
        $other_id = $request->input("other_id" ?? 0) ?? 0;
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        $data = $this->service->get_fans_list($this->user_id, $other_id, $page, $limit);
        return success("粉丝列表", $data);
    }
}
