<?php
namespace App\Api\Requests\Events;

use Illuminate\Validation\Rule;

use App\Api\Requests\BaseRequest;

class UserOrderFeedbackRequest extends BaseRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            // 'order_no'=> ['required'],
            'title' => ['required'],
            'content' => ['required'],
        ];
    }

    public function messages(){
        return [
            // 'order_no.required'=> '请选择订单',
            'title.required'=> '请输入举报原因',
            'content.required'=> '请输入举报内容',
        ];
    }
}