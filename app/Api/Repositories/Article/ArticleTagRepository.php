<?php
namespace App\Api\Repositories\Article;

use App\Models\Article\ArticleTag as Model;
use Illuminate\Support\Facades\Cache;

class ArticleTagRepository{
    protected $eloquentClass = Model::class;

    public function use_ids_get_datas(array $ids){
        $ids_str = json_encode($ids);
        return Cache::tags(['tags'])->remember("tags:{$ids_str}", 864000, function() use($ids){
            return $this->eloquentClass::id($ids)->select(['id', 'name', 'image'])->get();
        });
    }

    public function del_cache(){
        // 直接删除全部缓存(方便,不会有bug)
        Cache::tags("tags")->flush();
    }
}