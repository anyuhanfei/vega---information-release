<?php
namespace App\Api\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Api\Repositories\Log\LogUserPayRepository;
use App\Api\Repositories\Idx\IdxSettingRepository;
use App\Api\Repositories\User\UsersRepository;
use App\Api\Repositories\Events\EventsRepository;
use App\Api\Repositories\Events\EventOrderRepository;
use App\Api\Repositories\Log\LogUserVipRepository;

use App\Api\Tools\Aliyun\AliyunPayTool;
use App\Api\Tools\Wx\WxPayTool;

class PayService{
    protected $pay_type = ['充值'];

    /**
     * 充值功能-第三方支付调用
     *
     * @param integer $user_id
     * @param integer|float|string $amount
     * @param string $pay_method
     * @return void
     */
    public function recharge_pay(int $user_id, int|float|string $amount, string $pay_method){
        return $this->pay($pay_method, $user_id, $amount, '', '充值', '充值' . $amount);
    }

    /**
     * 购买vip，直接调用支付，成功后再添加vip记录
     *
     * @param integer $user_id
     * @param string $vip_name
     * @param string $pay_method
     * @return void
     */
    public function buy_vip(int $user_id, string $vip_name, string $pay_method){
        $vip = (new IdxSettingRepository())->use_vipname_get_one_data($vip_name);
        if(!$vip){
            throwBusinessException("选择的vip模式不存在");
        }
        $user = (new UsersRepository())->use_id_get_one_data($user_id);
        if($user->vip != ''){
            $vip_level = ['包月'=> 1, '包季'=> 2, '包年'=> 3];
            if($vip_level[$user->vip] > $vip_level[$vip->value0]){
                throwBusinessException("当前您是{$user->vip}会员, 请购买{$user->vip}以上等级进行续费");
            }
        }
        // 支付
        // $pay_data = $this->pay($pay_method, $user_id, $vip->price, $vip->name, '开通vip', '开通vip' . $vip->name);
        // 测试阶段直接完成
        $pay_data = $this->开通vip($user_id, $vip_name);
        return $pay_data;
    }


    /**
     * 调用第三方支付
     *
     * @param string $pay_method 支付类型，alipay 支付宝，wx 微信
     * @param int $user_id 会员id
     * @param int $money 支付金额
     * @param string $remark 备注，每种支付场景备注的情况不同
     * @param string $order_type 订单类型，支付场景
     * @param string $subject 商品名
     * @return void
     */
    public function pay($pay_method, $user_id, $money, $remark = '', $order_type = '', $subject = ''){
        // 创建支付订单
        $LogUserPayRepository = new LogUserPayRepository();
        $order_no = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        $res = $LogUserPayRepository->create_data($user_id, $order_no, $pay_method, $order_type, $money, $remark, 'app');
        // 调用第三方支付
        switch($pay_method){
            case 'app支付宝':
                return ((new AliyunPayTool()))->index($subject, $order_no, $money);
                break;
            case "h5支付宝":
                break;
            case "app微信":
                break;
            case "h5微信":
                return ((new WxPayTool()))->jsapi_pay($user_id, $subject, $order_no, $money);
                break;
            case "小程序微信":
                return ((new WxPayTool()))->mini_pay($user_id, $subject, $order_no, $money);
                break;
            default:
                throwBusinessException('支付调用失败');
                break;
        }
    }

    /**
     * 阿里云支付回调
     *
     * @param array $request
     * @return bool
     */
    public function alipay_notify(array $params):bool{
        // 验证
        $result = (new AliyunPayTool())->notify_verify($params);
        Log::debug("支付宝支付：" . json_encode($result));
        $res = $this->notify_execute($params['out_trade_no']);
        return $res;
    }

    /**
     * 微信支付回调
     *
     * @param array $request
     * @return bool
     */
    public function wxpay_notify(array $params):bool{
        // 验证
        $result = (new WxPayTool())->notify_verify();
        Log::debug("微信支付：" . json_encode($result));
        // 回调
        $res = $this->notify_execute($result['resource']['ciphertext']['out_trade_no']);
        return $res;
    }

    /**
     * 支付回调后，逻辑执行
     *
     * @param string $order_no 订单编号，数据的唯一标识，由支付方回调数据中获得
     * @return boolean
     */
    protected function notify_execute(string $order_no):bool{
        // 获取支付记录
        $LogUserPayRepository = new LogUserPayRepository();
        $log = $LogUserPayRepository->use_order_no_get_one_data($order_no);
        if(!$log || $log->status != 1){
            return true;
        }
        // 根据记录类型做支付成功后的逻辑处理
        DB::beginTransaction();
        try{
            // 将支付记录修改为已处理(已回调)
            $res_one = $LogUserPayRepository->update_log_over_pay($order_no);
            if(!$res_one){
                throwBusinessException('支付记录状态修改失败');
            }
            // 这里根据支付记录中存储的订单类型执行对应的支付成功的后续操作
            switch($log->order_type){
                case "活动报名":
                    $this->活动报名($log->remark);
                    break;
                case "发布活动":
                    $this->发布活动($log->remark, $log->money);
                    break;
                case "开通vip":
                    $this->开通vip($log->user_id, $log->remark);
                    break;
                default:
                    break;
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            throwBusinessException($e->getMessage());
        }
        return true;
    }

    public function 活动报名(string $order_no){
        $event_order = (new EventOrderRepository())->use_order_no_get_one_data($order_no);
        if($event_order){
            (new EventOrderRepository())->use_order_no_update_status($order_no, 10);
        }
        return true;
    }

    public function 发布活动(int $id, int|float|string $money){
        $event = (new EventsRepository())->use_id_get_one_data($id);
        if($event){
            (new EventsRepository())->use_id_update_status_10($id, floatval($money));
        }
        return true;
    }

    public function 开通vip(int $user_id, string $vip_name){
        $vip = (new IdxSettingRepository())->use_vipname_get_one_data($vip_name);
        if($vip){
            // 给会员添加vip时间
            $res_data = (new UsersRepository())->update_vip($user_id, $vip->value0, $vip->value4);
            // 添加vip购买记录
            (new LogUserVipRepository())->create_data($user_id, $vip_name, $vip->value4, $res_data['start_time']);
        }
        return true;
    }
}
