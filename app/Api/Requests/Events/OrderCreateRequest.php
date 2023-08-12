<?php
namespace App\Api\Requests\Events;

use Illuminate\Validation\Rule;

use App\Api\Requests\BaseRequest;

use App\Models\Event\Events as EventsModel;


class OrderCreateRequest extends BaseRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'event_id'=> ['required'],
            'number'=> ['required', "min:1"],
            'information'=> ['required'],
            'pay_method'=> ['required', Rule::in(['app微信', 'app支付宝'])],
        ];
    }

    public function messages(){
        return [
            'event_id.required'=> '请选择活动',
            'number.required'=> '请选择报名人数',
            'number.in'=> '报名人数不能低于1人',
            'information.required'=> '请填写报名信息',
            'pay_method.required'=> '请选择支付方式',
            'pay_method.in'=> '请选择正确的支付方式',
        ];
    }
}