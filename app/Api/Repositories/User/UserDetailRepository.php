<?php
namespace App\Api\Repositories\User;

use App\Models\User\UserDetail as Model;

class UserDetailRepository{
    protected $eloquentClass = Model::class;

    /**
     * 创建会员账号时，初始化会员详情
     *
     * @param integer $user_id
     * @return void
     */
    public function create_data(int $user_id){
        return $this->eloquentClass::create([
            'id'=> $user_id
        ]);
    }

    /**
     * 修改指定会员的数据
     *
     * @param integer $id
     * @param array $datas
     * @return void
     */
    public function update_user_detail(int $id, array $datas){
        return $this->eloquentClass::id($id)->update($datas);
    }
}