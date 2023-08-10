<?php

namespace App\Models\Event;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\BaseFilter;


/**
 * 会员表
 */
class EventQa extends Model{
	use HasDateTimeFormatter;
    use SoftDeletes;
    use BaseFilter;

    protected $guarded = [];

    /*------------------------关联----------------------------------------*/
    /**
     * 报名人会员
     *
     * @return void
     */
    public function user(){
        return $this->hasOne(\App\Models\User\Users::class, "user_id", 'id');
    }

    /**
     * 活动
     *
     * @return void
     */
    public function event(){
        return $this->hasOne(\App\Models\Event\Events::class, "event_id", 'id');
    }

    /**
     * 发布者会员id
     *
     * @return void
     */
    public function publisher(){
        return $this->hasOne(\App\Models\User\Users::class, 'publisher_id', 'id');
    }

    /**
     * 问题
     *
     * @return void
     */
    public function question(){
        return $this->hasOne(\App\Models\Event\EventQa::class, 'question_id', 'id');
    }


/*------------------------查询----------------------------------------*/
    public function scopeUserId(Builder $builder, int $value){
        return $builder->where("user_id", $value);
    }

    public function scopeEventId(Builder $builder, int $value){
        return $builder->where("event_id", $value);
    }

    public function scopePublisherId(Builder $builder, int $value){
        return $builder->where("publisher_id", $value);
    }
}
