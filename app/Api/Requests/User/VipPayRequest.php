<?php
namespace App\Api\Requests\User;

use Illuminate\Validation\Rule;

use App\Api\Requests\BaseRequest;


class VipPayRequest extends BaseRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'vip_name'=> ['required'],
            'pay_method'=> ['required', Rule::in(['app微信', 'app支付宝'])],
        ];
    }

    public function messages(){
        return [
            'vip_name.required'=> '请选择VIP模式',
            'pay_method.in'=> '请选择正确的支付方式',
            'pay_method.required'=> '请选择支付方式',
        ];
    }
}