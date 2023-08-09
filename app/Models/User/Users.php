<?php

namespace App\Models\User;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\BaseFilter;

use App\Models\Log\LogUserFund;
use App\Models\Log\LogUserOperation;


/**
 * 会员表
 */
class Users extends Model{
	use HasDateTimeFormatter;
    use SoftDeletes;
    use BaseFilter;

    protected $guarded = [];

/*------------------------关联----------------------------------------*/
    public function funds(){
        return $this->hasOne(UserFunds::class, 'id', 'id');
    }

    public function detail(){
        return $this->hasOne(UserDetail::class, 'id', 'id');
    }

    public function parent(){
        return $this->hasOne(self::class, 'id', 'parent_id');
    }

    public function log_fund(){
        return $this->hasMany(LogUserFund::class, 'id', 'uid');
    }

    public function log_operation(){
        return $this->hasMany(LogUserOperation::class, 'id', 'uid');
    }

/*------------------------查询----------------------------------------*/
    public function scopeId(Builder $builder, int $value){
        return $builder->where("id", $value);
    }

    public function scopeNickname(Builder $builder, string $value){
        return $builder->where("nickname", 'like', '%'.$value.'%');
    }

    public function scopeEmail(Builder $builder, string $value){
        return $builder->where("email", 'like', '%'.$value.'%');
    }

    public function scopePhone(Builder $builder, string $value){
        return $builder->where("phone", 'like', '%'.$value.'%');
    }

    public function scopeAccount(Builder $builder, string $value){
        return $builder->where("account", 'like', '%'.$value.'%');
    }

    public function scopeParentId(Builder $builder, string $value){
        return $builder->where("parent_id", $value);
    }

    public function scopeOpenid(Builder $builder, string $value){
        return $builder->where("openid", $value);
    }

    public function scopeThirdParty(Builder $builder, string $value){
        return $builder->where("third_party", $value);
    }

    public function scopeIsLogin(Builder $builder, string $value){
        return $builder->where("is_login", $value);
    }
}
