<?php
namespace App\Api\Rules\User;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;

use App\Api\Repositories\User\UsersRepository;


class UserIdExistVerify implements Rule, DataAwareRule{
    protected $data = [];

    public function setData($data){
        $this->data = $data;
        return $this;
    }

    public function passes($attribute, $value){
        $user_repository = new UsersRepository();
        $data = $user_repository->use_id_get_one_data($value);
        return boolval($data);
    }

    public function message(){
        return "当前账号已注销";
    }
}