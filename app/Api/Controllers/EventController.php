<?php
namespace App\Api\Controllers;

use Illuminate\Http\Request;

use App\Api\Controllers\BaseController;
use App\Api\Services\EventService;

/**
 * 活动控制器
 */
class EventController extends BaseController{
    protected $service;

    public function __construct(Request $request, EventService $EventService){
        parent::__construct($request);
        $this->service = $EventService;
    }

    /**
     * 获取活动分类
     *
     * @param Request $request
     * @return void
     */
    public function event_category(Request $request){
        $parent_id = $request->input('parent_id' ?? 0) ?? 0;
        $data = $this->service->get_event_category($parent_id);
        return success("活动分类", $data);
    }

    /**
     * 创建活动
     *
     * @param Request $request
     * @return void
     */
    public function event_create(\App\Api\Requests\Events\EventCreateRequest $request){
        $params['event_type'] = $request->input("event_type");
        $params['title'] = $request->input("title");
        $params['sex_limit'] = $request->input("sex_limit");
        $params['charge_type'] = $request->input("charge_type");
        $params['award_content'] = $request->input("award_content");
        $params['site_address'] = $request->input("site_address");
        $params['site_longitude'] = $request->input("site_longitude");
        $params['site_latitude'] = $request->input("site_latitude");
        $params['start_time'] = $request->input("start_time");
        $params['end_time'] = $request->input("end_time");
        $params['category_id'] = $request->input("category_id");
        $params['require_content'] = $request->input("require_content");
        $params['image'] = $request->input("image");
        $params['video'] = $request->input("video");
        $params['service_phone'] = $request->input("service_phone");
        $params['information_of_registration_key'] = $request->input("information_of_registration_key");
        $pay_method = $request->input("pay_method");
        $pay_data = $this->service->create_event_operation($this->user_id, $pay_method, $params);
        return success("创建活动成功", ['pay_data'=> $pay_data]);
    }

    /**
     * 修改活动信息，审核驳回后使用
     *
     * @param \App\Api\Requests\Events\EventUpdateRequest $request
     * @return void
     */
    public function event_update(\App\Api\Requests\Events\EventUpdateRequest $request){
        $event_id = $request->input("event_id");
        $params['event_type'] = $request->input("event_type");
        $params['title'] = $request->input("title");
        $params['sex_limit'] = $request->input("sex_limit");
        $params['charge_type'] = $request->input("charge_type");
        $params['award_content'] = $request->input("award_content");
        $params['site_address'] = $request->input("site_address");
        $params['site_longitude'] = $request->input("site_longitude");
        $params['site_latitude'] = $request->input("site_latitude");
        $params['start_time'] = $request->input("start_time");
        $params['end_time'] = $request->input("end_time");
        $params['category_id'] = $request->input("category_id");
        $params['require_content'] = $request->input("require_content");
        $params['image'] = $request->input("image");
        $params['video'] = $request->input("video");
        $params['service_phone'] = $request->input("service_phone");
        $params['information_of_registration_key'] = $request->input("information_of_registration_key");
        $pay_data = $this->service->update_event_operation($this->user_id, $event_id, $params);
        return success("修改活动成功，请等待审核");
    }

    /**
     * 获取活动列表
     *
     * @param \App\Api\Requests\PageRequest $request
     * @return void
     */
    public function event_list(\App\Api\Requests\PageRequest $request){
        $page = $request->input("page");
        $limit = $request->input("limit");
        $search['title'] = $request->input("title", '') ?? '';
        $search['sex'] = $request->input("sex", '') ?? '';
        $search['longitude'] = $request->input("longitude", 0) ?? 0;
        $search['latitude'] = $request->input("latitude", 0) ?? 0;
        $search['date'] = $request->input("date", '') ?? '';
        $search['category_id'] = $request->input("category_id", '') ?? '';
        $list = $this->service->get_event_list($this->user_id, $page, $limit, $search);
        return success("活动列表", $list);
    }

    /**
     * 活动详情（展示用）
     *
     * @param Request $request
     * @return void
     */
    public function event_detail(Request $request){
        $event_id = $request->input("event_id", 0) ?? 0;
        $data = $this->service->get_event_detail($this->user_id, $event_id);
        return success("活动详情", $data);
    }

    /**
     * 他人活动列表
     *
     * @param \App\Api\Requests\Events\OtherEventListRequest $request
     * @return void
     */
    public function other_event_list(\App\Api\Requests\Events\OtherEventListRequest $request){
        $other_id = $request->input("other_id", 0) ?? 0;
        $status = $request->input("status");
        $page = $request->input("page");
        $limit = $request->input("limit");
        $status = $status == "进行中" ? [20, 30] : [40];
        $data = $this->service->get_user_event_list($this->user_id, $page, $limit, $other_id, $status);
        return success("他人活动列表", $data);
    }

    /**
     * 我的活动列表
     *
     * @param \App\Api\Requests\Events\UserEventListRequest $request
     * @return void
     */
    public function user_event_list(\App\Api\Requests\Events\UserEventListRequest $request){
        $status = $request->input("status");
        $page = $request->input("page");
        $limit = $request->input("limit");
        $status = $status == 'all' ? [0, 10, 19, 20, 30, 40] : [$status];
        $data = $this->service->get_user_event_list($this->user_id, $page, $limit, 0, $status);
        return success("我的活动列表", $data);
    }

    /**
     * 获取我的获取详情（修改活动信息用）
     */
    public function user_event_detail(Request $request){
        $event_id = $request->input("event_id" ?? 0) ?? 0;
        $data = $this->service->get_user_event_detail($this->user_id, $event_id);
        return success("我的活动详情", $data);
    }

    /**
     * 会员取消活动
     *
     * @param Request $request
     * @return void
     */
    public function user_event_cancel(Request $request){
        $event_id = $request->input("event_id" ?? 0) ?? 0;
        $res = $this->service->user_event_cancel_operation($this->user_id, $event_id);
        return success("取消成功");
    }
}