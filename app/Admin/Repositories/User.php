<?php

namespace App\Admin\Repositories;

use App\Models\User\Users as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class User extends EloquentRepository{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;

    public function get_parent_list(){
        return $this->eloquentClass::all()->pluck('nickname', 'id');
    }

    /**
     * 通过会员id集获取会员的标识信息并转换成数组
     *
     * @param string $ids_str
     * @return void
     */
    public function use_ids_get_identities_arr(string $ids_str){
        $sys_user = config('admin.users');
        $identity = $sys_user['user_identity'][0];
        return $this->eloquentClass::whereIn('id', explode(',', $ids_str))->pluck($identity)->toArray();
    }
}
