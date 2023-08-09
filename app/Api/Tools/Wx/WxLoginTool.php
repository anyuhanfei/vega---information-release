<?php
namespace App\Api\Tools\Wx;

use Illuminate\Support\Facades\Http;

use App\Api\Repositories\User\UsersRepository;

use App\Api\Services\UserLoginService;


/**
 * 微信公众号登录
 */
class WxLoginTool{
    protected $appid;
    protected $appsecret;

    public function __construct(){
        $this->appid = env("WX_APPID");
        $this->appsecret = env("WX_APPSECRET");
    }

    /**
     * 获取用户在公众号中的信息
     * 包含用户注册逻辑
     * TODO::当前逻辑为，通过code获取 access_token, 然后通过 access_token 获取用户信息，如果openid不存在则注册账号
     *
     * @param string $code
     * @return string openid
     */
    public function get_openid(string $code, bool $is_register = true):string{
        $tokens = $this->access_token($code);
        $user_info = $this->get_userinfo($tokens['access_token'], $tokens['openid']);
        // 查询是否存在此用户，如果不存在则注册
        $UsersRepository = new UsersRepository();
        $user = $UsersRepository->use_openid_get_one_data($user_info['openid'], '微信公众号');
        if(!$user && $is_register){
            (new UserLoginService())->register('', '', '', '', '', $user_info['headimgurl'], $user_info['nickname'], '', 0, '微信公众号', $user_info['openid'], '');
        }
        return $user_info['openid'];
    }

    private function access_token(string $code):array{
        $token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->appid . '&secret=' . $this->appsecret . '&code=' . $code . '&grant_type=authorization_code';
        $token = json_decode(file_get_contents($token_url));
        if(isset($token->errcode)){
            throwBusinessException($token->errcode . ': ' . $token->errmsg);
        }
        return [
            'access_token'=> $token->access_token,
            'refresh_token'=> $token->refresh_token,
            'openid'=> $token->openid
        ];
    }

    private function refresh_token(string $refresh_token):array{
        $access_token_url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=' . $this->appid . '&grant_type=refresh_token&refresh_token=' . $refresh_token;
        $access_token = json_decode(file_get_contents($access_token_url));
        if(isset($access_token->errcode)){
            throwBusinessException($access_token->errcode . ': ' . $access_token->errmsg);
        }
        return [
            'access_token'=> $access_token->access_token,
            'refresh_token'=> $access_token->refresh_token,
            'openid'=> $access_token->openid
        ];
    }

    private function get_userinfo(string $access_token, string $openid):array{
        $user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token . '&openid=' . $openid . '&lang=zh_CN';
        $user_info = json_decode(file_get_contents($user_info_url));
        if(isset($user_info->errcode)){
            throwBusinessException($user_info->errcode . ': ' . $user_info->errmsg);
        }
        return [
            'openid'=> $user_info->openid,
            'nickname'=> $user_info->nickname,
            'sex'=> $user_info->sex,
            'language'=> $user_info->language,
            'city'=> $user_info->city,
            'province'=> $user_info->province,
            'country'=> $user_info->country,
            'headimgurl'=> $user_info->headimgurl,
        ];
    }
}

/**
 * 以上三个接口的具体返回内容：
 *  https://api.weixin.qq.com/sns/oauth2/access_token：
 *   object(stdClass)#1777 (5) {
 *       ["access_token"]=> string(110) "66_DtB6.............KYOVcaXQ"
 *       ["expires_in"]=> int(7200)
 *       ["refresh_token"]=> string(110) "66_8g-.............i8TCGhfc"
 *       ["openid"]=> string(28) "ohyvq6axEpySwbGGfIOxV0a1QTrw"
 *       ["scope"]=> string(15) "snsapi_userinfo"
 *   }
 *
 * https://api.weixin.qq.com/sns/oauth2/refresh_token
 *  object(stdClass)#1776 (5) {
 *       ["openid"]=> string(28) "ohyvq6axEpySwbGGfIOxV0a1QTrw"
 *       ["access_token"]=> string(110) "66_DtB6.............KYOVcaXQ"
 *       ["expires_in"]=> int(7200)
 *       ["refresh_token"]=> string(110) "66_8g-.............i8TCGhfc"
 *       ["scope"]=> string(15) "snsapi_userinfo"
 *   }
 *
 * https://api.weixin.qq.com/sns/userinfo
 *  object(stdClass)#1779 (9) {
 *       ["openid"]=> string(28) "ohyvq6axEpySwbGGfIOxV0a1QTrw"
 *       ["nickname"]=> string(6) "奈亚"
 *       ["sex"]=> int(0)
 *       ["language"]=> string(0) ""
 *       ["city"]=> string(0) ""
 *       ["province"]=> string(0) ""
 *       ["country"]=> string(0) ""
 *       ["headimgurl"]=> string(131) "https://thirdwx.qlogo.cn/mmopen/vi_32/2M99jhPTcZDd3510WQmTC3CHj4s30oMzvnH4H6YMlE0hd7jCibztudNDYibyWYm7tBBic6RR7VMsH3JdurDAXB8Ug/132"
 *       ["privilege"]=> array(0) { }
 *   }
 */