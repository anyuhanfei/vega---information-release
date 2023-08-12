<?php
namespace App\Api\Rules\User;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;

use App\Api\Repositories\User\UserAlbumRepository;

/**
 * 是否是会员所属的影集
 */
class UserAlbumVerify implements Rule, DataAwareRule{
    protected $data = [];

    public function setData($data){
        $this->data = $data;
        return $this;
    }

    public function passes($attribute, $value){
        $album = (new UserAlbumRepository())->use_id_get_one_data($value);
        if(!$album || $album->user_id != $this->data['user_id']){
            return false;
        }
        return true;
    }

    public function message(){
        return "当前影集不存在";
    }
}