<?php
namespace App\Api\Rules\Events;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;

use App\Api\Repositories\Events\EventsRepository;


class EventUserVerify implements Rule, DataAwareRule{
    protected $data = [];

    public function setData($data){
        $this->data = $data;
        return $this;
    }

    public function passes($attribute, $value){
        $data = (new EventsRepository())->use_id_get_one_data($value);
        return $data && $data->user_id == $this->data['user_id'];
    }

    public function message(){
        return "不是你举办的活动";
    }
}