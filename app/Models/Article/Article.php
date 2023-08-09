<?php

namespace App\Models\Article;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\BaseFilter;

use App\Models\Article\ArticleCategory;

/**
 * 文章表
 */
class Article extends Model{
	use HasDateTimeFormatter;
    use SoftDeletes;
    use BaseFilter;

    protected $table = 'article';
    protected $guarded = [];

    public function category(){
        return $this->hasOne(ArticleCategory::class, 'id', "category_id")->withTrashed()->select(['id', 'name', 'image']);
    }

    public function scopeId(Builder $builder, int $value){
        return $builder->where("id", $value);
    }

    public function scopeCategoryId(Builder $builder, int $value){
        return $builder->where("category_id", $value);
    }

    public function scopeTitle(Builder $builder, string $value){
        return $builder->where("title", 'like', '%'.$value.'%');
    }

    public function scopeAuthor(Builder $builder, string $value){
        return $builder->where("author", 'like', '%'.$value.'%');
    }

    public function scopeIntro(Builder $builder, string $value){
        return $builder->where("intro", 'like', '%'.$value.'%');
    }

    public function scopeKeyword(Builder $builder, string $value){
        return $builder->where("keyword", 'like', '%'.$value.'%');
    }

    public function scopeContent(Builder $builder, string $value){
        return $builder->where("content", 'like', '%'.$value.'%');
    }
}
