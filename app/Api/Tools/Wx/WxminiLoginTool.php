<?php
namespace App\Api\Tools\Wx;

use Illuminate\Support\Facades\Http;

use App\Api\Repositories\User\UsersRepository;

use App\Api\Services\UserLoginService;

/**
 * 微信小程序授权登录
 */
class WxminiLoginTool{
    protected $appid;
    protected $secret;
    protected $repository;

    public function __construct(){
        //微信小程序配置
        $this->appid = env("WXMINI_APPID");
        $this->secret = env("WXMINI_SECRET");
        $this->repository = new UsersRepository();
    }

    /**
     * 获取openid， 如果此用户还没有注册，则直接注册
     *
     * 当前小程序的政策为：所有用户解析出来的昵称和头像都是微信默认，所以我们已经不需要 iv 和 encryptedData 参数来解析了。
     * 现在微信昵称和头像获取不到了，但是可以使用此方法解析手机号
     *
     * @param [type] $code
     * @return void
     */
    public function get_openid(string $code, int $parent_id):string{
        $data = $this->jscode2session($code);
        $nickname = "微信用户";
        $avatar = "https://thirdwx.qlogo.cn/mmopen/vi_32/POgEwh4mIHO4nibH0KlMECNjjGxQUq24ZEaGT4poC6icRiccVGKSyXwibcPq4BWmiaIGuG1icwxaQX6grC9VemZoJ8rg/132";
        $user = $this->repository->use_openid_get_one_data($data['openid'], "微信小程序");
        if(!$user){
            (new UserLoginService())->register('', '', '', '', '', $avatar, $nickname, '', $parent_id, '微信小程序', $data['openid'], '');
        }
        $this->repository->save_session_key($data['openid'], $data['sessionkey']);
        return $data['openid'];
    }

    /**
     * 通过 iv 和 encryptedData 解析到手机号
     *
     * @param string $openid
     * @param string $iv
     * @param string $encryptedData
     * @return string phone
     */
    public function get_wx_phone(string $openid, string $iv, string $encryptedData):string{
        $session_key = $this->repository->get_session_key($openid);
        $user_info = $this->decryptData($this->appid, $session_key, $encryptedData, $iv);
        try{
            return $user_info['phoneNumber'];
        }catch(\Exception $e){
            throwBusinessException($e->getMessage());
        }
    }

    /**
     * 访问微信小程序接口，获取会员openid
     *
     * @param string $code
     * @return void
     */
    private function jscode2session(string $code):array{
        $api = "https://api.weixin.qq.com/sns/jscode2session?appid={$this->appid}&secret={$this->secret}&js_code={$code}&grant_type=authorization_code";
        $res = json_decode(Http::get($api), true);
        if(!empty($res['errcode'])){
            throwBusinessException($res['errcode']);
        }
        return [
            'session_key'=> $res['session_key'],
            'openid'=> $res['openid'],
        ];
    }

    /**
     * 检验数据的真实性，并且获取解密后的明文.
     * @param $encryptedData string 加密的用户数据
     * @param $iv string 与用户数据一同返回的初始向量
     * @param $data string 解密后的原文
     *
     * @return array 返回数据
     */
    private function decryptData($appid, $sessionKey, $encryptedData, $iv){
        if(strlen($sessionKey) != 24){
            throwBusinessException('encodingAesKey 非法');
        }
        $aesKey = base64_decode($sessionKey);
        if(strlen($iv) != 24){
            throwBusinessException('aes 解密失败');
        }
        $aesIV = base64_decode($iv);
        $aesCipher = base64_decode($encryptedData);
        $result = openssl_decrypt($aesCipher, "aes-128-cbc", $aesKey, OPENSSL_RAW_DATA, $aesIV);
        $result = $this->decode($result);
        $dataObj = json_decode($result);
        if($dataObj == NULL){
            throwBusinessException('解密后得到的buffer非法');
        }
        if($dataObj->watermark->appid != $appid){
            throwBusinessException('base64解密失败');
        }
        $data = json_decode($result, true);
        return $data;
    }

    private function decode($text){
        $pad = ord(substr($text, -1));
        if ($pad < 1 || $pad > 32) {
            $pad = 0;
        }
        return substr($text, 0, (strlen($text) - $pad));
    }
}