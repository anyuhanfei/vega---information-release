<?php
namespace App\Api\Repositories\Sys;

use App\Models\Sys\SysAd as Model;
use Illuminate\Support\Facades\Cache;

class SysAdRepository{
    protected $eloquentClass = Model::class;

    /**
     * 获取数据，如果是广告位则需要获取当前广告位下的全部广告
     *
     * @param integer $id
     * @return void
     */
    public function use_id_get_one_data(int $id){
        $res = Cache::remember("ad:{$id}", 864000, function() use($id){
            return $this->eloquentClass::with(['parent', 'children'])->where("id", $id)->first();
        });
        return $res;
    }

    /**
     * 删除 get_data 方法中产生的缓存
     */
    public function del_cache(int $id){
        Cache::forget("ad:{$id}");
    }
}