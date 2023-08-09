<?php
namespace App\Api\Repositories\Article;

use App\Models\Article\Article as Model;

use Illuminate\Support\Facades\Cache;

class ArticleRepository{
    protected $eloquentClass = Model::class;

    /**
     * 获取列表
     * 省略了详情、时间的数据
     *
     * @param integer $page
     * @param integer $limit
     * @return void
     */
    public function get_list(int $page, int $limit){
        return Cache::tags(['article'])->remember("article:all:{$page}:{$limit}", 864000, function() use($page, $limit){
            return $this->eloquentClass::with(['category'])->page($page, $limit)->orderBy("id", 'desc')->select(["id", "category_id", "tag_ids", "title", "author", "intro", "keyword", "image", "created_at"])->get();
        });
    }

    /**
     * 根据分类获取列表
     * 省略了详情、时间数据
     *
     * @param integer $category_id
     * @param integer $page
     * @param integer $limit
     * @return void
     */
    public function use_category_get_list(int $category_id, int $page, int $limit){
        return Cache::tags(['article'])->remember("article:{$category_id}:{$page}:{$limit}", 864000, function() use($category_id, $page, $limit){
            return $this->eloquentClass::with(['category'])->categoryId($category_id)->page($page, $limit)->orderBy("id", 'desc')->select(["id", "category_id", "tag_ids", "title", "author", "intro", "keyword", "image", "created_at"])->get();
        });
    }

    /**
     * 根据id获取详情
     *
     * @param integer $id
     * @return void
     */
    public function use_id_get_one_data(int $id){
        return Cache::remember("article:detail:{$id}", 864000, function() use($id){
            return $this->eloquentClass::with(['category'])->id($id)->select(["id", "category_id", "tag_ids", "title", "author", "intro", "keyword", "image", "content", "created_at"])->first();
        });
    }

    public function del_cache(int $id){
        // 列表数据全部删除(添加和删除都会修改每页获取到的数据，修改只会修改当页的数据但单独列出会增加复杂度)
        Cache::tags("article")->flush();
        // 删除对应id的详情缓存
        Cache::forget("article:detail:{$id}");
    }
}