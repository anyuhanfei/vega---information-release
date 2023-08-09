<?php

namespace App\Admin\Repositories\Sys;

use App\Models\Sys\SysAd as Model;
use Dcat\Admin\Repositories\EloquentRepository;


class SysAd extends EloquentRepository{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;

    /**
     * 获取广告的位置(上级)信息
     *
     * @param int $parent_id
     * @return void
     */
    public function get_parent(int $parent_id){
        return $this->eloquentClass::where('id', $parent_id)->first();
    }

    /**
     * 获取所有的位置信息
     *
     * @return void
     */
    public function get_parent_list(){
        return $this->eloquentClass::where('parent_id', 0)->get()->pluck('title', 'id');
    }

}
