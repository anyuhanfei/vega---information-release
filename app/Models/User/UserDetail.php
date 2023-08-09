<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\BaseFilter;


/**
 * 会员详情表
 */
class UserDetail extends Model{
    use BaseFilter;

    public $timestamps = false;
    protected $guarded = [];
    protected $table = "user_detail";

    public function scopeId(Builder $builder, int $value){
        return $builder->where("id", $value);
    }
}
