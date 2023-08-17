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

    /**
     * 获取服务的全部订单(用户的基本信息)
     *
     * @param \App\Api\Requests\Events\EventOrdersRequest $request
     * @return void
     */
    public function event_orders(\App\Api\Requests\Events\EventOrdersRequest $request){
        $event_id = $request->input('event_id', 0) ?? 0;
        $type = $request->input('type');
        $data = $this->service->get_event_orders($this->user_id, $event_id, $type);
        return success("订单列表", $data);
    }

    /**
     * 审核订单
     *
     * @param \App\Api\Requests\Events\EventOrderAuditRequest $request
     * @return void
     */
    public function event_order_audit(\App\Api\Requests\Events\EventOrderAuditRequest $request){
        $order_no = $request->input('order_no');
        $status = $request->input('status');
        $res = $this->service->audit_order_operation($this->user_id, $order_no, $status);
        return success("审核订单完成");
    }

    /**
     * 他人参与的活动列表
     *
     * @param \App\Api\Requests\PageRequest $request
     * @return void
     */
    public function other_orders(\App\Api\Requests\PageRequest $request){
        $other_id = $request->input("other_id");
        $page = $request->input("page");
        $limit = $request->input("limit");
        $data = $this->service->get_other_orders($other_id, $page, $limit);
        return success("他人的订单", $data);
    }

    /**
     * 自己参与的活动列表
     *
     * @param \App\Api\Requests\Events\UserOrdersRequest $request
     * @return void
     */
    public function user_orders(\App\Api\Requests\Events\UserOrdersRequest $request){
        $status = $request->input('status');
        $page = $request->input("page");
        $limit = $request->input("limit");
        $data = $this->service->get_user_orders($this->user_id, $status, $page, $limit);
        return success("我的订单", $data);
    }

    /**
     * 自己参与的活动的详情
     *
     * @param Request $request
     * @return void
     */
    public function user_order_detail(Request $request){
        $order_no = $request->input("order_no" ?? '') ?? '';
        $data = $this->service->get_user_order_detail($this->user_id, $order_no);
        return success("我的订单详情", $data);
    }

    /**
     * 会员取消参加活动的订单
     *
     * @param Request $request
     * @return void
     */
    public function user_order_cancel(Request $request){
        $order_no = $request->input("order_no" ?? '') ?? '';
        $res = $this->service->cancel_user_order_operation($this->user_id, $order_no);
        return success("取消成功");
    }

    /**
     * 会员评价订单
     *
     * @param \App\Api\Requests\Events\UserOrderEvaluateRequest $request
     * @return void
     */
    public function user_order_evaluate(\App\Api\Requests\Events\UserOrderEvaluateRequest $request){
        $order_no = $request->input("order_no");
        $score = $request->input("score");
        $tags = $request->input("tags" ?? '') ?? '';
        $res = $this->service->evaluate_user_order_operation($this->user_id, $order_no, $score, $tags);
        return success("评价成功");
    }

    /**
     * 会员提交意见反馈
     *
     * @param Request $request
     * @return void
     */
    public function user_order_feedback(\App\Api\Requests\Events\UserOrderFeedbackRequest $request){
        $order_no = $request->input('order_no');
        $title = $request->input("title");
        $content = $request->input("content");
        $images = $request->input("images" ?? '') ?? '';
        $video = $request->input("video" ?? '') ?? '';
        $res = $this->service->feedback_user_order_operation($this->user_id, $order_no, $title, $content, $images, $video);
        return success("提交成功");
    }
}
