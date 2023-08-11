<?php
namespace App\Api\Rules\User;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;

use App\Api\Repositories\Idx\IdxSettingRepository;
use App\Api\Repositories\User\UserTagsRepository;

/**
 * 会员自己设置标签验证
 */
class UserSetTagsExistVerify implements Rule, DataAwareRule{
    protected $data = [];

    public function setData($data){
        $this->data = $data;
        return $this;
    }

    public function passes($attribute, $value){
        // 必须验证是否已存在
        $data = (new UserTagsRepository())->use_tag_get_user_set_one_data($this->data['user_id'], $value);
        if($data){
            return false;
        }
        return true;
    }

    public function message(){
        return "当前标签已存在";
    }
}