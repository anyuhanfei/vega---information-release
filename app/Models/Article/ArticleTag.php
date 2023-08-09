<?php

namespace App\Models\Article;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\BaseFilter;

/**
 * 文章标签表
 */
class ArticleTag extends Model{
	use HasDateTimeFormatter;
    use SoftDeletes;
    use BaseFilter;

    protected $table = 'article_tag';
    protected $guarded = [];

    public function scopeId(Builder $builder, array $value){
        return $builder->whereIn("id", $value);
    }

    public function scopeName(Builder $builder, string $value){
        return $builder->where("name", $value);
    }
}
