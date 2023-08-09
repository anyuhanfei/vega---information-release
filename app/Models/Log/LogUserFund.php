<?php
namespace App\Models\Log;

use App\Models\User\Users;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
use App\Models\BaseFilter;
use Illuminate\Database\Eloquent\Model;


/**
 * 会员资产记录表
 */
class LogUserFund extends Model{
	use HasDateTimeFormatter;
    use BaseFilter;
    protected $table = 'log_user_fund';

    protected $guarded = [];

    public function user(){
        return $this->hasOne(Users::class, 'id', 'user_id')->withTrashed();
    }

    public function scopeCoinType(Builder $builder, string $value){
        return $builder->where('coin_type', $value);
    }

    public function scopeUid(Builder $builder, int $value){
        return $builder->where('uid', $value);
    }

    protected function CoinType(): Attribute{
        $coin_type = config('admin.users.user_funds');
        if(count($coin_type) == 0){
            return Attribute::make(
                get: fn ($value) => '未设置',
            );
        }
        return Attribute::make(
            get: fn ($value) => $coin_type[$value],
        );
    }
}
