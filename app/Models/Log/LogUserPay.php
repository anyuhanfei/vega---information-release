<?php

namespace App\Models\Log;

use App\Models\User\Users;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\BaseFilter;

use Illuminate\Database\Eloquent\Model;

/**
 * 支付记录表
 */
class LogUserPay extends Model{
	use HasDateTimeFormatter;
    use BaseFilter;

    protected $table = 'log_user_pay';
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(Users::class)->withTrashed();
    }

    public function scopeOrderNo(Builder $builder, string $value){
        return $builder->where('order_no', $value);
    }

    public function scopePlatform(Builder $builder, string $value){
        return $builder->where('platform', $value);
    }

    public function scopeStatus(Builder $builder, string $value){
        return $builder->where('status', $value);
    }

    public function scopeRemark(Builder $builder, string $value){
        return $builder->where('remark', $value);
    }
}
