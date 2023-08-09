<?php
namespace App\Api\Rules;

use App\Api\Repositories\User\UsersRepository;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;


class AccountExistVerify implements Rule, DataAwareRule{
    protected $data = [];

    public function setData($data){
        $this->data = $data;
        return $this;
    }

    public function passes($attribute, $value){
        $user_repository = new UsersRepository();
        $data = $user_repository->use_phone_get_one_data($value);
        return !boolval($data);
    }

    public function message(){
        return "此手机号已被注册";
    }
}