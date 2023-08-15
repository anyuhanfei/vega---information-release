<?php
namespace App\Api\Requests;

use Illuminate\Validation\Rule;

class UserOrdersRequest extends BaseRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'status'=> ['required', Rule::in(['全部', '报名中', '审核中', '进行中', '待评价', '已完成'])],
            'page' => ['required', 'min:1'],
            'limit' => ['required', 'min:1', 'max:100'],
        ];
    }

    public function messages(){
        return [
            'status.required'=> "请选择状态",
            'status.in'=> "请选择正确的状态",
            'page.required'=> '请指定页码',
            'page.min'=> '页码必须大于等于1',
            'limit.required'=> '请指定每页数据数量',
            'limit.min'=> '每页数据数量必须大于等于1',
            'limit.max'=> '每页数据数量必须小于100',
        ];
    }
}