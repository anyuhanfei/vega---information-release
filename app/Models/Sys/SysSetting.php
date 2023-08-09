<?php

namespace App\Models\Sys;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\BaseFilter;


/**
 * 系统设置表
 */
class SysSetting extends Model{
	use HasDateTimeFormatter;
    use SoftDeletes;
    use BaseFilter;

    protected $table = 'sys_setting';
    protected $guarded = [];

    public function scopeId(Builder $builder, int $value){
        return $builder->where("id", $value);
    }

    public function scopeParentId(Builder $builder, int $value){
        return $builder->where("parent_id", $value);
    }

    public function scopeTitle(Builder $builder, string $value){
        return $builder->where('title', 'like', '%'.$value.'%');
    }
}
