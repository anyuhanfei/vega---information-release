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
        do{
            $value = Redis::get("setting:{$id}");
        }while($value === null && $this->set_redis($id));
        return $value;
    }

    /**
     * 将数据添加到redis
     * 在后台编辑或添加都会加入redis，此项是为了防止获取无效的数据和redis没有持久化
     *
     * @param int $id 系统设置id
     * @return void
     */
    private function set_redis($id){
        $data = $this->eloquentClass::where('id', $id)->first();
        $value = '';
        if($data){
            $value = $data->value;
            if($data->input_type == 'select'){
                $value = explode(',', $data->remark)[$data->value];
            }
        }
        $res = Redis::setnx("setting:{$id}", $value);
        return $res;
    }
}