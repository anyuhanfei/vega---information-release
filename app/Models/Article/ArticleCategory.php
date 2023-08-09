<?php

namespace App\Models\Article;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\BaseFilter;

/**
 * 文章分类表
 */
class ArticleCategory extends Model{
	use HasDateTimeFormatter;
    use SoftDeletes;
    use BaseFilter;

    protected $table = 'article_category';
    protected $guarded = [];

    public function scopeId(Builder $builder, int $value){
        return $builder->where("id", $value);
    }

    public function scopeName(Builder $builder, string $value){
        return $builder->where("name", $value);
    }

}
