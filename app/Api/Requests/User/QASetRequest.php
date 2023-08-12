<?php
namespace App\Api\Requests\User;

use App\Api\Requests\BaseRequest;


class QASetRequest extends BaseRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'event_id'=> ['required', new \App\Api\Rules\Events\EventVerify()],
            'question_id'=> [new \App\Api\Rules\Events\QuestionVerify()],
            'content'=> ['required'],
        ];
    }

    public function messages(){
        return [
            'event_id.required'=> '请选择活动',
            'content.required'=> '请填写内容',
        ];
    }
}