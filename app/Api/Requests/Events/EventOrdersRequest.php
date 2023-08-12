<?php
namespace App\Api\Requests\Events;

use Illuminate\Validation\Rule;

use App\Api\Requests\BaseRequest;


class EventOrdersRequest extends BaseRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'event_id'=> ['required', new \App\Api\Rules\Events\EventVerify(), new \App\Api\Rules\Events\EventUserVerify()],
            'type'=> ['required', Rule::in(['待审核', '已通过'])],
        ];
    }

    public function messages(){
        return [
            'event_id.required'=> '请选择活动',
            'pay_method.required'=> '请选择状态',
            'pay_method.in'=> '请选择正确的状态',
        ];
    }
}