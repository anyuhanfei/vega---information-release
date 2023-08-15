<?php

namespace App\Models\Sys;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\BaseFilter;

/**
 * 轮播图表
 */
class SysBanner extends Model{
	use HasDateTimeFormatter;
    use SoftDeletes;
    use BaseFilter;

    protected $table = 'sys_banner';
    protected $guarded = [];

    public function scopeSite(Builder $builder, string $value){
        return $builder->where("site", $value);
    }

    /**
     * 轮播图位置设置
     *
     * @return void
     */
    public static function site_array(){
        return [
            '首页',
        ];
    }
}
