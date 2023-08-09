<?php
namespace App\Api\Repositories\Sys;

use Illuminate\Support\Facades\Cache;

use App\Models\Sys\SysBanner as Model;


class SysBannerRepository{
    protected $eloquentClass = Model::class;

    /**
     * 根据指定位置获取对应的轮播图数据
     */
    public function 获取指定位置数据(string $site){
        return Cache::tags("banner:{$site}")->remember("banner:{$site}", 1200, function() use($site){
            return $this->eloquentClass::site($site)->select(['id', 'image', 'url', 'created_at'])->get();
        });
    }

    /**
     * 删除缓存
     *
     * @return void
     */
    public function del_cache(string $site = ''){
        // 删除 获取指定位置数据() 方法产生的缓存
        foreach($this->eloquentClass::site_array() as $value){
            if($site == '' || $value == $site){
                Cache::tags("banner:{$site}")->flush();
            }
        }
    }
}