<?php
namespace App\Api\Controllers;

use Illuminate\Http\Request;
use App\Api\Controllers\BaseController;

use App\Api\Services\PayService;

use App\Api\Tools\IosPayTool;


/**
 * 支付类
 */
class PayController extends BaseController{

    protected $service;

    public function __construct(Request $request, PayService $PayService){
        parent::__construct($request);
        $this->service = $PayService;
    }

    /**
     * 测试-充值
     *
     * @param Request $request
     * @return void
     */
    public function recharge(Request $request){
        $amount = $request->input('amount', 0) ?? 0;
        $pay_method = $request->input('pay_method', '') ?? '';
        $data = $this->service->recharge_pay($this->user_id, $amount, $pay_method);
        return success("充值支付调用", $data);
    }

    /**
     * 开通vip支付申请
     *
     * @param Request $request
     * @return void
     */
    public function vip(Request $request){
        $vip_name = $request->input('vip_name', '') ?? '';
        $pay_method = $request->input('pay_method', '') ?? '';
        $pay_data = $this->service->buy_vip($this->user_id, $vip_name, $pay_method);
        return success("开通vip", ['pay_data'=> $pay_data]);
    }

    /**
     * 测试-ios支付
     *
     * @param Request $request
     * @return void
     */
    public function ios_pay(Request $request){
        $receipt_data = $request->input('receipt-data', '');
        $ios_pay_tool = new IosPayTool();
        $ios_pay_tool->pay($receipt_data);
        return success("支付成功");
    }

    /**
     * 支付宝回调
     *
     * @param Request $request
     * @return void
     */
    public function alipay_notify(Request $request){
        $PayService = new PayService();
        return success("回调", $PayService->alipay_notify($request->input()));
    }

    /**
     * 微信回调
     *
     * @param Request $request
     * @return void
     */
    public function wxpay_notify(Request $request){
        $PayService = new PayService();
        return success("回调", $PayService->wxpay_notify($request->input()));
    }
}
