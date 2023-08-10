<?php
namespace App\Api\Requests\Events;

use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\Rule;

use App\Api\Requests\BaseRequest;

use App\Models\Event\Events as EventsModel;


class EventCreateRequest extends BaseRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        $event_model = (new EventsModel());
        return [
            'event_type'=> ['required', Rule::in($event_model->event_type_array())],
            'title'=> ['required'],
            'sex_limit'=> ['required', Rule::in($event_model->sex_limit_array())],
            'charge_type'=> ['required', Rule::in($event_model->charge_type_array())],
            'award_content'=> ['required'],
            'site_address'=> ['required'],
            'site_longitude'=> ['required'],
            'site_latitude'=> ['required'],
            'start_time'=> ['required', 'date', "after:tomorrow"],
            'end_time'=> ['required', 'date', "after:start_time"],
            'category_id'=> ['required', new \App\Api\Rules\Events\EventCategoryVerify()],
            'require_content'=> ['required'],
            'image'=> ['required'],
            'video'=> ['required'],
            'service_phone'=> ['required'],
            'information_of_registration_key'=> ['required'],
        ];
    }

    public function messages(){
        return [
            'event_type.required'=> '请选择活动类型',
            'event_type.in'=> '请选择正确的活动类型',
            'title.required'=> '请填写活动标题',
            'sex_limit.required'=> '请选择性别限制',
            'sex_limit.in'=> '请选择正确的性别限制',
            'charge_type.required'=> '请选择收费方式',
            'charge_type.in'=> '请选择正确的收费方式',
            'award_content.required'=> '请填写奖励说明',
            'site_address.required'=> '请选择举办区域',
            'site_longitude.required'=> '请选择举办区域',
            'site_latitude.required'=> '请选择举办区域',
            'start_time.required'=> '请选择举办时间',
            'start_time.date'=> '请选择今日后的时间',
            'start_time.after'=> '请选择今日后的时间',
            'end_time.required'=> '请选择结束时间',
            'end_time.date'=> '结束时间必须在举办时间之后',
            'end_time.after'=> "结束时间必须在举办时间之后",
            'category_id.required'=> '请选择活动分类',
            'require_content.required'=> '请填写活动要求内容',
            'image.required'=> '请上传活动介绍图文',
            'video.required'=> '请上传活动介绍视频',
            'service_phone.required'=> '请填写主办方电话',
            'information_of_registration_key.required'=> '请选择报名填写信息表',
        ];
    }
}