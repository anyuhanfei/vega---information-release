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
     * 账号-密码注册
     *
     * @param \App\Api\Requests\Login\AccountRegisterRequest $request
     * @return void
     */
    public function account_register(\App\Api\Requests\Login\AccountRegisterRequest $request){
        $account = $request->input('account');
        $password = $request->input('password');
        $this->service->register($account, '', '', $password);
        return success('注册成功');
    }

    /**
     * 邮箱-密码注册
     *
     * TODO::未实现发送邮箱验证码
     * @return void
     */
    public function email_register(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');
        $this->service->register('', '', $email, $password);
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
     * 账号-密码登录
     *
     * @param Request $request
     * @return void
     */
    public function account_password_login(Request $request){
        $account = $request->input('account');
        $password = $request->input('password');
        return success('登录成功', $this->service->login('account_password', [
            'identity_type'=> 'account',
            'account'=> $account,
            'password'=> $password
        ]));
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
     * 邮箱-密码登录
     *
     * @param Request $request
     * @return void
     */
    public function email_password_login(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');
        return success('登录成功', $this->service->login('email_password', [
            'identity_type'=> 'email',
            'email'=> $email,
            'password'=> $password
        ]));
    }

    //TODO::这之下未测试
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
     * 微信小程序登录
     *
     * @param Request $request
     * @return void
     */
    public function wxmini_login(Request $request){
        $code = $request->input('code', '');
        $iv = $request->input('iv', '') ?? '';
        $encryptedData = $request->input('encryptedData', '') ?? '';
        $parent_id = $request->input('parent_id', '') ?? '';
        // 此步骤会自动注册
        $wxmini_tool = new WxminiRegisterTool();
        $openid = $wxmini_tool->get_openid($code, $iv, $encryptedData, $parent_id);
        return success('登录成功', $this->service->login('wxmini', ['openid'=> $openid]));
    }

    /**
     * 微信公众号登录(第三方登录)
     *
     * @param Request $request
     * @return void
     */
    public function wx_login(Request $request){
        $code = $request->input('code', '') ?? '';
        $WxLoginTool = new WxLoginTool();
        $openid = $WxLoginTool->get_openid($code);
        return success('登录成功', $this->service->login('wx', ['openid'=> $openid]));
    }
}
