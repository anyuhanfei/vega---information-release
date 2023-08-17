<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\BaseFilter;


/**
 * 会员表
 */
class EventOrderEvaluates extends Model{
	use HasDateTimeFormatter;
    use BaseFilter;

    protected $guarded = [];

    public function is_anonymity_array(){
        return ['非匿名', '匿名'];
    }

    public function score_array(){
        return ["✩✩✩✩✩", "✭✩✩✩✩", "✭✭✩✩✩", "✭✭✭✩✩", "✭✭✭✭✩", "✭✭✭✭✭"];
    }

    protected function tags_text(): Attribute{
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => comma_str_to_array($attributes['tags']),
        );
    }


    /*------------------------关联----------------------------------------*/
    /**
     * 报名人会员
     *
     * @return void
     */
    public function user(){
        return $this->hasOne(\App\Models\User\Users::class, "id", 'user_id');
    }

    /**
     * 活动
     *
     * @return void
     */
    public function event(){
        return $this->hasOne(\App\Models\Event\Events::class, "id", 'event_id');
    }

    /**
     * 发布者会员id
     *
     * @return void
     */
    public function publisher(){
        return $this->hasOne(\App\Models\User\Users::class, 'id', 'publisher_id');
    }



/*------------------------查询----------------------------------------*/
    public function scopeOrderNo(Builder $builder, int $value){
        return $builder->where("order_no", $value);
    }

    public function scopeUserId(Builder $builder, int $value){
        return $builder->where("user_id", $value);
    }

    public function scopeEventId(Builder $builder, int $value){
        return $builder->where("event_id", $value);
    }

    public function scopePublisherId(Builder $builder, int $value){
        return $builder->where("publisher_id", $value);
    }

    public function scopeInformation(Builder $builder, int $value){
        return $builder->where("information_of_registration_value", 'like', '%'.$value.'%');
    }

    public function scopeStatus(Builder $builder, int|array $value){
        if(is_int($value)){
            $value = [$value];
        }
        return $builder->whereIn("status", $value);
    }
}
