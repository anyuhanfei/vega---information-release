<?php

namespace App\Models\Event;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Dcat\Admin\Traits\ModelTree;
use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

/**
 * 广告表
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
        return $this->hasOne(SysAd::class, 'id', 'parent_id');
    }

    /**
     * 获取下级分类
     *
     * @return void
     */
    public function children(){
        return $this->hasMany(SysAd::class, 'parent_id', 'id');
    }
}
