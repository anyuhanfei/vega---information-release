<?php

namespace App\Models\Sys;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\BaseFilter;


/**
 * 系统公告表
 */
class SysNotice extends Model{
	use HasDateTimeFormatter;
    use SoftDeletes;
    use BaseFilter;

    protected $table = 'sys_notice';
    protected $guarded = [];

    public static function init(){
        if(self::count() < 1){
            self::create([
                'title'=> '',
                'image'=> '',
                'content'=> '',
            ]);
        }
    }

    public function scopeId(Builder $builder, int $value){
        return $builder->where('id', $value);
    }
}
