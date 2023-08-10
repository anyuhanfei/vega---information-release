<?php

namespace App\Models\Log;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * 系统消息记录表
 */
class LogUserVip extends Model{
	use HasDateTimeFormatter;

    protected $table = 'log_user_vip';
    protected $guarded = [];

    public function user(){
        return $this->hasOne(\App\Models\User\Users::class, 'id', 'user_id')->withTrashed();
    }

    public function vip(){
        return $this->hasOne(\App\Models\Idx\IdxSetting::class, 'id', "vip_id");
    }

    public function status_array(){
        return ['未支付', '已支付'];
    }

    protected function status_text(): Attribute{
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $this->status_array()[$attributes['status']],
        );
    }

    /*------------------------查询----------------------------------------*/
    public function scopeUserId(Builder $builder, int $value){
        return $builder->where("user_id", $value);
    }

    public function scopeVipId(Builder $builder, int $value){
        return $builder->where("vip_id", $value);
    }
}
