<?php
namespace App\Api\Controllers;

use Illuminate\Http\Request;

use App\Api\Controllers\BaseController;

use App\Api\Services\UserService;


class UserController extends BaseController{
    /**
     * 会员信息详情
     *
     * @return void
     */
    public function detail(Request $request){
        $other_id = $request->input("other_id", 0) ?? 0;
        $data = (new UserService())->get_user_detail($this->user_id, $other_id);
        return success('会员详情', $data);
    }

    /**
     * 修改会员信息
     *
     * @param Request $request
     * @return void
     */
    public function update_data(Request $request){
        $res = (new UserService())->update_datas($this->user_id, $request->all());
        return $res ? success('编辑成功') : error('编辑失败');
    }

    /**
     * 修改密码，输入旧密码和新密码
     *
     * @param \App\Api\Requests\Password\UpdatePasswordRequest $request
     * @return void
     */
    public function update_password(\App\Api\Requests\Password\UpdatePasswordRequest $request){
        $password = $request->input('password');
        $res = (new UserService())->update_datas($this->user_id, ['password'=> $password]);
        return $res ? success('密码修改成功') : error('密码修改失败');
    }

    /**
     * 忘记密码，输入手机验证码和新密码
     *
     * @param \App\Api\Requests\Password\ForgetPasswordRequest $request
     * @return void
     */
    public function forget_password(\App\Api\Requests\Password\ForgetPasswordRequest $request){
        $password = $request->input('password');
        $res = (new UserService())->update_datas($this->user_id, ['password'=> $password]);
        return $res ? success('密码修改成功') : error('密码修改失败');
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
        $res = (new UserService())->set_tags_operation($this->user_id, $type, $tag);
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
        $data = (new UserService())->tag_like_operation($this->user_id, $tag_id);
        return success("点赞成功", $data);
    }
}
