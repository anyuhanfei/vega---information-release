<?php
namespace App\Api\Repositories\Sys;

use App\Models\Sys\SysSetting as Model;
use Illuminate\Support\Facades\Redis;


/**
 * 系统设置数据仓库
 * 只有后台有权限修改数据，所以在后台要做缓存删除或更新
 */
class SysSettingRepository{
    protected $eloquentClass = Model::class;

    /**
     * 获取指定的系统设置
     *
     * @param int $id 系统设置id
     * @return void
     */
    public function use_id_get_value($id){
        return $this->eloquentClass::id($id)->value('value');
    }
}