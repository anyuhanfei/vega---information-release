<?php
namespace App\Api\Rules\Events;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;

use App\Api\Repositories\Events\EventQaRepository;


class QuestionVerify implements Rule, DataAwareRule{
    protected $data = [];

    public function setData($data){
        $this->data = $data;
        return $this;
    }

    public function passes($attribute, $value){
        if($value == 0 || $value == '' || $value == null){
            return true;
        }
        $qa_data = (new EventQaRepository())->use_id_get_one_data($value);
        if(!$qa_data || $qa_data->event_id != $this->data['event_id']){
            // 不存在 或 问题不是这个活动的
            return false;
        }
        return true;
    }

    public function message(){
        return "此活动不存在或已下架";
    }
}