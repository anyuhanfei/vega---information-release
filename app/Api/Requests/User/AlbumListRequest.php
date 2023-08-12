<?php
namespace App\Api\Requests\User;

use App\Api\Requests\BaseRequest;

use Illuminate\Validation\Rule;

class AlbumListRequest extends BaseRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'type'=> ['required', Rule::in(['图片', '视频'])],
            'page' => ['required', 'min:1'],
            'limit' => ['required', 'min:1', 'max:100'],
        ];
    }

    public function messages(){
        return [
            'type.required'=> '请选择类型',
            'type.in'=> '请选择正确的类型',
            'page.required'=> '请指定页码',
            'page.min'=> '页码必须大于等于1',
            'limit.required'=> '请指定每页数据数量',
            'limit.min'=> '每页数据数量必须大于等于1',
            'limit.max'=> '每页数据数量必须小于100',
        ];
    }
}