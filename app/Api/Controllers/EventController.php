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
        $res = $this->service->create_event_operation($this->user_id, $params);
        return success("创建活动成功");
    }
}