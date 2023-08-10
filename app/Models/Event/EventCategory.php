<?php

namespace App\Models\Event;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Dcat\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Builder;


/**
 * 活动分类表
 */
class EventCategory extends Model{
	use HasDateTimeFormatter;
    use SoftDeletes;
    use ModelTree;

    protected $table = 'event_category';
    protected $titleColumn = 'name';
    protected $parentColumn = 'parent_id';
    protected $guarded = [];

    public function getOrderColumn(){
        return null;
    }

    /**
     * 获取上级分类
     *
     * @return void
     */
    public function parent(){
        return $this->hasOne(EventCategory::class, 'id', 'parent_id');
    }

    /**
     * 获取下级分类
     *
     * @return void
     */
    public function children(){
        return $this->hasMany(EventCategory::class, 'parent_id', 'id');
    }

    public function scopeId(Builder $builder, int $value){
        return $builder->where("id", $value);
    }

    public function scopeParentId(Builder $builder, int $value){
        return $builder->where("parent_id", $value);
    }
}
