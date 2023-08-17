<?php
namespace App\Api\Repositories\Log;

use App\Models\Log\LogWithdraw as Model;

class LogWithdrawRepository{
    protected $eloquentClass = Model::class;

    public function create_data(int $user_id, string $money, string $type, string $account, string $username){
        return $this->eloquentClass::create([
            'user_id'=> $user_id,
            'money'=> $money,
            'type'=> $type,
            'account'=> $account,
            'username'=> $username,
        ]);
    }
}