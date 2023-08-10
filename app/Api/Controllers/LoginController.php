<?php
namespace App\Api\Controllers;

use Illuminate\Http\Request;

use App\Api\Controllers\BaseController;

use App\Api\Services\UserLoginService;

use App\Api\Tools\YidunMobileTool;
use App\Api\Tools\Wx\WxminiRegisterTool;
use App\APi\Tools\Wx\WxLoginTool;


class LoginController extends BaseController{
    protected $service = null;

    public function __construct(Request $request, UserLoginService $UserLoginService){
        parent::__construct($request);
        $this->service = $UserLoginService;
    }

    /**
     * 手机号-密码注册
     *
     * @return void
     */
    public function phone_register(\App\Api\Requests\Login\PhoneRegisterRequest $request){
        $phone = $request->input('phone');
        $password = $request->input('password');
        $this->service->register('', $phone, '', $password);
        return success('注册成功');
    }

    /**
     * 手机号-短信验证码登录(未注册直接登录)
     *
     * @param Request $request
     * @return void
     */
    public function phone_smscode_login(\App\Api\Requests\Login\SmscodeLoginRequest $request){
        $phone = $request->input('phone');
        return success('登录成功', $this->service->login('phone_smscode', ['phone'=> $phone]));
    }

    /**
     * 手机号-密码登录
     *
     * @param Request $request
     * @return void
     */
    public function phone_password_login(Request $request){
        $phone = $request->input('phone');
        $password = $request->input('password');
        return success('登录成功', $this->service->login('phone_password', [
            'identity_type'=> 'phone',
            'phone'=> $phone,
            'password'=> $password
        ]));
    }

    /**
     * 易盾一键登录
     *
     * @param Request $request
     * @return void
     */
    public function yidun_oauth_login(Request $request){
        $token = $request->input('token', '');
        $accessToken = $request->input('accessToken', '');
        $res = YidunMobileTool::oauth($token, $accessToken);
        $phone = $res['data']['phone'];
        return success('登录成功', $this->service->login('yidun_oauth', ['phone'=> $phone]));
    }

    /**
     * 忘记密码
     *
     * @param \App\Api\Requests\Login\SmscodeLoginRequest $request
     * @return void
     */
    public function forget_password(\App\Api\Requests\Login\SmscodeLoginRequest $request){
        $phone = $request->input('phone');
        $password = $request->input('password');
        $this->service->forget_password($phone, $password);
        return success('密码重置成功');
    }
}
