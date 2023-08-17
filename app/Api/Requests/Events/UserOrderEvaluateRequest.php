<?php
namespace App\Api\Requests\Events;

use Illuminate\Validation\Rule;

use App\Api\Requests\BaseRequest;

class UserOrderEvaluateRequest extends BaseRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'order_no'=> ['required'],
            'score' => ['required', 'min:1', 'max:5'],
        ];
    }

    public function messages(){
        return [
            'order_no.required'=> '请选择订单',
            'score.required'=> '请选择评分',
            'score.min'=> '评分最低为1分',
            'score.max'=> '评分最高为5分',
        ];
    }
}