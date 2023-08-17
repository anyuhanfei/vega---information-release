<?php
namespace App\Api\Requests\User;

use Illuminate\Validation\Rule;

use App\Api\Requests\BaseRequest;


class WithdrawRequest extends BaseRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'money'=> ['required', 'min:1'],
            'type'=> ['required', Rule::in(['微信', '支付宝'])],
        ];
    }

    public function messages(){
        return [
            'money.required'=> '请输入金额',
            'money.min'=> "提现金额最低为1",
            'type.in'=> '请选择正确的提现方式',
            'type.required'=> '请选择提现方式',
        ];
    }
}