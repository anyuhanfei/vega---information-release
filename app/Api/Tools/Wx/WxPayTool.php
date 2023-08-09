<?php
namespace App\Api\Tools\Wx;

use Yansongda\Pay\Pay;

use App\Api\Repositories\User\UsersRepository;

/**
 * 微信支付
 */
class WxPayTool{
    protected $config;

    public function __construct(){
        $this->config = config("pay");
    }

    /**
     * 微信小程序支付，发起支付请求
     *
     * @param int $user_id 会员ID
     * @param string $subject 支付的商品名
     * @param string $order_no 支付记录的支付编号
     * @param int|float|string $amount 支付金额，单位元
     * @return void
     */
    public function mini_pay(int $user_id, string $subject, string $order_no, int|float|string $amount){
        // 构建微信小程序支付订单
        $user_repositories = new UsersRepository();
        $openid = $user_repositories->use_id_get_openid($user_id, '微信小程序');
        if($openid == ''){
            throwBusinessException("您尚未绑定微信小程序");
        }
        $order = [
            'out_trade_no'=> $order_no,
            '_config' => 'default',
            'description'=> $subject,
            'amount' => [
                'total' => intval($amount * 100),
                'currency' => 'CNY',
            ],
            'payer' => [
                'openid' => $openid,
            ]
        ];
        Pay::config($this->config);
        $result = Pay::wechat()->mini($order);
        \Illuminate\Support\Facades\Log::debug($result);
        return $result;
    }

    /**
     * 微信公众号支付
     *
     * @param int $user_id
     * @param string $subject 支付的商品名
     * @param string $order_no 支付记录的支付编号
     * @param int|float|string $amount 支付金额，单位元
     * @return void
     */
    public function jsapi_pay(int $user_id, string $subject, string $order_no, int|float|string $amount){
        Pay::config($this->config);
        $user_repository = new UsersRepository();
        $openid = $user_repository->use_id_get_openid($user_id, "微信公众号");
        if($openid == ''){
            throwBusinessException("您尚未绑定微信公众号");
        }
        $order = [
            'out_trade_no' => $order_no,
            'description' => $subject,
            'amount' => [
                'total' => intval($amount * 100),
            ],
            'payer' => [
                'openid' => $openid,
            ],
        ];
        $response = Pay::wechat()->mp($order);
        \Illuminate\Support\Facades\Log::debug($response);
        return $response;
    }

    /**
     * 退款
     *
     * @param string $order_no 此订单编号是支付记录的订单编号，而非订单数据中的订单编号
     * @param integer|float $money
     * @return void
     */
    public function refund(string $order_no, int|float $money){
        Pay::config($this->config);
        $order = [
            'out_trade_no' => $order_no,
            'out_refund_no' => '' . time(),
            'amount' => [
                'refund' => intval($money * 100),
                'total' => intval($money * 100),
                'currency' => 'CNY',
            ],
        ];
        $result = Pay::wechat()->refund($order);
        return $result;
    }

    /**
     * 支付回调验证
     * 微信的支付回调验证返回中包含订单编号
     *
     * @return void
     */
    public function notify_verify(){
        Pay::config($this->config);
        return Pay::wechat()->callback();
    }
}
