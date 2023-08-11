<?php
namespace App\Api\Rules\User;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;

use App\Api\Repositories\Idx\IdxSettingRepository;

/**
 * 会员自己设置标签验证
 */
class UserTagsExistVerify implements Rule, DataAwareRule{
    protected $data = [];

    public function setData($data){
        $this->data = $data;
        return $this;
    }

    public function passes($attribute, $value){
        // 如果是选择系统，则要判断是否存在
        if($this->data['type'] == '选自系统'){
            $data = (new IdxSettingRepository())->use_tag_get_one_data($value);
            if(!$data){
                return false;
            }
        }
        return true;
    }

    public function message(){
        return "请选择系统指定的标签";
    }
}