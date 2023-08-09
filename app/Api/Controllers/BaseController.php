<?php
namespace App\Api\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

use App\Api\Services\UserService;


class BaseController extends Controller{
    protected $user_id;
    protected $setting;

    public function __construct(Request $request){
        // 获取当前登录的会员信息
        if($request->hasHeader('token')){
            $user_service = new UserService();
            $this->user_id = $user_service->use_token_get_id($request->header('token'));
        }else{
            $this->user_i = 0;
        }
        // 获取部分系统设置
        $this->setting['identity_field'] = config('admin.users.user_identity')[0];
    }

    public function test(Request $request){
        $arr = $request->input("arr.*");
        var_dump($arr);exit;
    }
}
