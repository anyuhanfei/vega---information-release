<?php
namespace App\Api\Controllers;

use App\Api\Controllers\BaseController;
use Illuminate\Http\Request;

use App\Api\Tools\SmsTool;
use App\Api\Tools\FileUploadTool;


/**
 * 工具方法类
 */
class ToolsController extends BaseController{

    /**
     * 发送短信验证码
     * （api接口调用短信也就只有验证码场景）
     *
     * @param string $type 场景类型，register(注册), other(其他, 登录、忘记密码、修改密码等)
     * @param int $phone
     * @return void
     */
    public function send_sms(\App\Api\Requests\SendSmsRequest $request){
        $phone = $request->input('phone');
        $sms_tool = new SmsTool();
        $sms_tool->send_sms_code($phone);
        return success('发送成功');
    }

    /**
     * 上传文件
     *
     * @param resource $file 文件资源句柄
     * @return void
     */
    public function upload(Request $request){
        if($request->hasFile('file')){
            $file_upload_tool = new FileUploadTool();
            return success('上传成功', ['path'=> $file_upload_tool->upload_file($request->file('file'))]);
        }
       return error('上传文件不存在');
    }
}
