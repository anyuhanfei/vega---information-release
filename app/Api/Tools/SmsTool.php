<?php
namespace App\Api\Tools;

use Illuminate\Support\Facades\Redis;

class SmsTool{
    /**
     * 发送短信验证码
     *
     * @param string $type 场景类型，register(注册), other(其他, 登录、忘记密码、修改密码等)
     * @param int $phone
     * @return void
     */
    public function send_sms_code(int $phone){
        $sms_code = rand(100000, 999999);
        $this->save_smscode($phone, $sms_code);
        return $this->send('code', $phone, ['code'=> $sms_code]);
    }

    /**
     * 发送短信（提示语等非验证码场景）
     *
     * @param string|integer $phone
     * @param string $type
     * @param array $params
     * @return void
     */
    public function send_sms(string|int $phone, string $type, array $params = array()){
        switch($type){
            default:
                throwBusinessException('请传入正确的发送类型');
        }
        return $this->send($type, $phone, $params);
    }

    /**
     * 调用第三方短信发送接口
     *
     * @param int $phone
     * @param int $sms_code
     * @return bool
     */
    protected function send(string $type, string $phone, array $param = []){
        return true;
    }

    /**
     * 存储已发送的短信的验证码，便于后续验证
     *
     * @param string $phone
     * @param integer $sms_code
     * @return void
     */
    public function save_smscode(string $phone, int $sms_code):bool{
        Redis::setex("sms_code:{$phone}", 60 * 5, $sms_code);
        return true;
    }

    /**
     * 验证验证码是否正确
     *
     * @param string $phone
     * @param integer $sms_code
     * @return void
     */
    public function verify_smscode(string $phone, int $sms_code):bool{
        $res = Redis::get("sms_code:{$phone}") == $sms_code;
        if($res){
            Redis::del("sms_code:{$phone}");
        }
        return $res;
    }
}