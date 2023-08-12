<?php
namespace App\Api\Requests\User;

use Illuminate\Validation\Rule;

use App\Api\Requests\BaseRequest;


class AlbumAddRequest extends BaseRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'type'=> ['required', Rule::in(['图片', '视频']), new \App\Api\Rules\User\AlbumVideoVerify()],
            'image'=> ['required'],
        ];
    }

    public function messages(){
        return [
            'image.required'=> '请说明上传类型',
            'image.required'=> '请正确说明上传类型',
            'image.required'=> '请上传图片',
        ];
    }
}