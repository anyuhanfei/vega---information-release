<?php
namespace App\Api\Requests\Events;

use Illuminate\Validation\Rule;

use App\Api\Requests\BaseRequest;


class EventOrderAuditRequest extends BaseRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'order_no'=> ['required'],
            'status'=> ['required', Rule::in(['拒接', '同意'])],
        ];
    }

    public function messages(){
        return [
            'order_no.required'=> '请选择活动订单编号',
            'status.required'=> '请选择状态',
            'status.in'=> '请选择正确的状态',
        ];
    }
}