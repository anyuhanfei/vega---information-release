<?php
namespace App\Api\Rules\Events;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;

use App\Api\Repositories\Events\EventsRepository;


class EventVerify implements Rule, DataAwareRule{
    protected $data = [];

    public function setData($data){
        $this->data = $data;
        return $this;
    }

    public function passes($attribute, $value){
        $data = (new EventsRepository())->use_id_get_one_data($value);
        return $data && $data->status >= 20;
    }

    public function message(){
        return "此活动不存在或已下架";
    }
}