<?php
namespace App\Api\Rules\Events;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;

use App\Api\Repositories\Events\EventCategoryRepository;


class EventCategoryVerify implements Rule, DataAwareRule{
    protected $data = [];

    public function setData($data){
        $this->data = $data;
        return $this;
    }

    public function passes($attribute, $value){
        $event_category_repository = new EventCategoryRepository();
        $data = $event_category_repository->use_id_get_one_data($value);
        if($data && $data->parent_id != 0){
            return true;
        }
        return false;
    }

    public function message(){
        return "请正确选择活动分类";
    }
}