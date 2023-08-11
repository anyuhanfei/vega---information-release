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
     * 设置会员的经纬度信息
     *
     * @param \App\Api\Requests\CoordinateRequest $request
     * @return void
     */
    public function set_coordinate(\App\Api\Requests\CoordinateRequest $request){
        $longitude = $request->input('longitude');
        $latitude = $request->input('latitude');
        (new UserService())->set_user_coordinate($this->user_id, $longitude, $latitude);
        return success("设置成功");
    }
}
