<?php
namespace App\Api\Requests\User;

use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\Rule;

use App\Api\Requests\BaseRequest;

use App\Models\User\UserTags as UserTagsModel;


class UserTagsSetRequest extends BaseRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        $user_tags_model = (new UserTagsModel());
        return [
            'type'=> ['required', Rule::in($user_tags_model->type_array())],
            'tag'=> ['required', new \App\Api\Rules\User\UserTagsExistVerify(), new \App\Api\Rules\User\UserSetTagsExistVerify()],
        ];
    }

    public function messages(){
        return [
            'type.required'=> '请选择标签类型',
            'type.in'=> '请选择正确的标签类型',
            'tag.required'=> '请选择/填写标签',
        ];
    }
}