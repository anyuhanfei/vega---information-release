<?php
namespace App\Api\Rules;

use App\Models\User\Users;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Support\Facades\Redis;

use App\Api\Repositories\User\UsersRepository;


class SendSmsPhoneVerify implements Rule, DataAwareRule{
    protected $data = [];

    public function setData($data){
        $this->data = $data;
        return $this;
    }

    public function passes($attribute, $value){
        $users_repository = new UsersRepository();
        $user = $users_repository->use_phone_get_one_data($value);
        if($this->data['type'] == 'register'){
            return !boolval($user);
        }else{
            return boolval($user);
        }
    }

    public function message(){
        if($this->data['type'] == 'register'){
            return "此手机号已被注册";
        }else{
            return "此手机号未被注册";
        }
    }
}