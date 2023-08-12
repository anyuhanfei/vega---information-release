<?php
namespace App\Api\Controllers;

use Illuminate\Http\Request;

use App\Api\Controllers\BaseController;
use App\Api\Services\EventOrderService;

/**
 * 活动订单控制器
 */
class EventOrderController extends BaseController{
    protected $service;

    public function __construct(Request $request, EventOrderService $EventOrderService){
        parent::__construct($request);
        $this->service = $EventOrderService;
    }

    /**
     * 创建活动订单
     *
     * @param \App\Api\Requests\Events\OrderCreateRequest $request
     * @return void
     */
    public function order_create(\App\Api\Requests\Events\OrderCreateRequest $request){
        $event_id = $request->input('event_id', 0) ?? 0;
        $number = $request->input('number');
        $information = $request->input('information', '') ?? '';
        $pay_method = $request->input('pay_method', '') ?? '';
        $pay_data = $this->service->create_order_operation($this->user_id, $event_id, $number, $information, $pay_method);
        return success("创建成功", ['pay_data'=> $pay_data]);
    }

}