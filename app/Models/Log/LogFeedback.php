<?php

namespace App\Models\Log;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * 系统消息记录表
 */
class LogFeedback extends Model{
	use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'log_feedback';
    protected $guarded = [];

    public function user(){
        return $this->hasOne(\App\Models\User\Users::class, 'id', 'uid')->withTrashed();
    }

    public function is_reply_array(){
        return ['未回复', '已回复'];
    }

    protected function is_reply_text(): Attribute{
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $this->is_reply_array()[$attributes['is_reply']],
        );
    }

    /*------------------------查询----------------------------------------*/
    public function scopeUserId(Builder $builder, int $value){
        return $builder->where("user_id", $value);
    }
}
