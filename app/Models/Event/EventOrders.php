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
class EventOrders extends Model{
	use HasDateTimeFormatter;
    use SoftDeletes;
    use BaseFilter;

    protected $guarded = [];

    /**
     * 状态说明
     *
     * @return void
     */
    public function status_array(){
        return ['-1'=> '已取消', '0'=> '未支付', '10'=> '未审核', '19'=> '已拒绝', '20'=> '已通过', '30'=> '进行中', '40'=> '已完成', '50'=> '已评价'];
    }

    protected function status_text(): Attribute{
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $this->status_array()[$attributes['status']],
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
