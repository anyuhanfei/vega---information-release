<?php
namespace App\Api\Repositories\Article;

use App\Models\Article\ArticleCategory as Model;
use Illuminate\Support\Facades\Cache;

class ArticleCategoryRepository{
    protected $eloquentClass = Model::class;

    public function get_all_data(){
        return Cache::remember("article:category", 864000, function(){
            return $this->eloquentClass::orderBy("id", 'asc')->get();
        });
    }

    public function del_cache(){
        Cache::forget("article:category");
    }
}