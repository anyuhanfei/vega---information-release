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
class Events extends Model{
	use HasDateTimeFormatter;
    use SoftDeletes;
    use BaseFilter;

    protected $guarded = [];

    /**
     * 活动类型
     *
     * @return void
     */
    public function event_type_array(){
        return ['商业活动', '公益活动'];
    }

    /**
     * 性别限制
     *
     * @return void
     */
    public function sex_limit_array(){
        return ['男', '女', '全部'];
    }

    /**
     * 收费方式
     *
     * @return void
     */
    public function charge_type_array(){
        return ['收费', '免费', '男收费', '女收费'];
    }

    /**
     * 状态说明
     *
     * @return void
     */
    public function status_array(){
        return ['-1'=> "已取消", '0'=> '未支付', '10'=> '未审核', '19'=> '已驳回', '20'=> '报名中', '30'=> '进行中', '40'=> '已完成'];
    }

    protected function statusText(): Attribute{
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $this->status_array()[$attributes['status']],
        );
    }

    /*------------------------关联----------------------------------------*/
    /**
     * 发布者会员
     *
     * @return void
     */
    public function user(){
        return $this->hasOne(\App\Models\User\Users::class, "id", 'user_id');
    }

    public function one_level_category(){
        return $this->hasOne(\App\Models\Event\EventCategory::class, "id", 'one_level_category_id')->withTrashed();
    }

    public function two_level_category(){
        return $this->hasOne(\App\Models\Event\EventCategory::class, "id", 'two_level_category_id')->withTrashed();
    }

/*------------------------查询----------------------------------------*/
    public function scopeId(Builder $builder, int|array $value){
        if(is_int($value)){
            $value = [$value];
        }
        return $builder->whereIn("id", $value);
    }

    public function scopeUserId(Builder $builder, int $value){
        return $builder->where("user_id", $value);
    }

    public function scopeTitle(Builder $builder, int $value){
        return $builder->where("title", 'like', '%'.$value.'%');
    }

    public function scopeSexLimit(Builder $builder, string $value){
        if($value == '女' || $value == '男'){
            $value = [$value, '全部'];
        }else{
            $value = [$value];
        }
        return $builder->whereIn("sex_limit", $value);
    }

    public function scopeChargeType(Builder $builder, int $value){
        return $builder->where("charge_type", $value);
    }

    public function scopeSiteAddress(Builder $builder, int $value){
        return $builder->where("site_address", 'like', '%'.$value.'%');
    }

    public function scopeOneLevelCategoryId(Builder $builder, int $value){
        return $builder->where("one_level_category_id", $value);
    }

    public function scopeTwoLevelCategoryId(Builder $builder, int $value){
        return $builder->where("two_level_category_id", $value);
    }

    public function scopeStatus(Builder $builder, int|array $value){
        if(is_int($value)){
            $value = [$value];
        }
        return $builder->whereIn("status", $value);
    }
}
