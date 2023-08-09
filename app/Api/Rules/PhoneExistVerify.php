<?php
namespace App\Api\Rules;

use App\Api\Repositories\User\UsersRepository;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;


class PhoneExistVerify implements Rule, DataAwareRule{
    protected $data = [];

    public function setData($data){
        $this->data = $data;
        return $this;
    }

    public function passes($attribute, $value){
        $user_repository = new UsersRepository();
        $data = $user_repository->use_account_get_one_data($value);
        return !boolval($data);
    }

    public function message(){
        return "此账号已被注册";
    }
}