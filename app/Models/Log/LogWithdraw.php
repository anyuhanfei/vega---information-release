<?php

namespace App\Models\Log;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * 会员提现申请表
 */
class LogWithdraw extends Model{
	use HasDateTimeFormatter;

    protected $table = 'log_withdraw';
    protected $guarded = [];

    public function user(){
        return $this->hasOne(\App\Models\User\Users::class, 'id', 'user_id')->withTrashed();
    }

    public function status_array(){
        return ['待审核', '已通过', '已驳回'];
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

    public function scopeId(Builder $builder, int $value){
        return $builder->where("id", $value);
    }
}
