<?php
namespace App\Api\Requests\Events;

use Illuminate\Validation\Rule;

use App\Api\Requests\BaseRequest;

class UserEventListRequest extends BaseRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'status'=> ['required', Rule::in(['all', 10, 19, 20, 30, 40])],
            'page' => ['required', 'min:1'],
            'limit' => ['required', 'min:1', 'max:100'],
        ];
    }

    public function messages(){
        return [
            'status.required'=> '请选择活动状态',
            'status.in'=> '请正确选择活动状态',
            'page.required'=> '请指定页码',
            'page.min'=> '页码必须大于等于1',
            'limit.required'=> '请指定每页数据数量',
            'limit.min'=> '每页数据数量必须大于等于1',
            'limit.max'=> '每页数据数量必须小于100',
        ];
    }
}