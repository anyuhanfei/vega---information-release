<?php
namespace App\Api\Repositories\Log;

use App\Models\Log\LogUserPay as Model;

class LogUserPayRepository{
    protected $eloquentClass = Model::class;

    /**
     * 创建支付订单
     *
     * @param integer $user_id 会员id
     * @param string $pay_no 支付记录流水号，这个是要传给第三方支付的编号
     * @param string $pay_method 支付类型：app支付宝、h5支付宝、app微信、h5微信、小程序微信
     * @param string $order_type 订单类型，这个是为了区分业务逻辑中的多个场景的支付
     * @param string $money 支付金额
     * @param string $remark 备注，各场景中表示的含义不同，如商城下单就可以表示订单号
     * @param string $platform 平台，默认为app
     * @return void
     */
    public function create_data(int $user_id, string $pay_no, string $pay_method, string $order_type, string $money, string $remark = '', string $platform = 'app'){
        return $this->eloquentClass::create([
            'order_no'=> $pay_no,
            'uid'=> $user_id,
            'type'=> $pay_method,
            'order_type'=> $order_type,
            'money'=> $money,
            'platform'=> 'app',
            'status'=> 1,
            'remark'=> $remark
        ]);
    }

    /**
     * 根据订单编号获取支付记录
     *
     * @param string $order_no
     * @return void
     */
    public function use_order_no_get_one_data(string $order_no){
        return $this->eloquentClass::orderNo($order_no)->first();
    }

    /**
     * 将支付日志的状态改为已支付
     *
     * @param string $order_no
     * @return void
     */
    public function update_log_over_pay(string $order_no){
        return $this->eloquentClass::orderNo($order_no)->update(['status'=> 2]);
    }
}