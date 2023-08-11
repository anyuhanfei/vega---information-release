<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\BaseFilter;
use Illuminate\Support\Facades\Bus;

/**
 * 会员标签表
 */
class UserTags extends Model{
    use BaseFilter;

    public $timestamps = false;
    protected $guarded = [];
    protected $table = "user_tags";

    public function type_array(){
        return ['选自系统', '自定义', '他人评价'];
    }

    public function scopeId(Builder $builder, int $value){
        return $builder->where("id", $value);
    }

    public function scopeUserId(Builder $builder, int $value){
        return $builder->where("user_id", $value);
    }

    public function scopeTag(Builder $builder, string $value){
        return $builder->where("tag", $value);
    }

    public function scopeType(Builder $builder, string|array $value){
        if(is_string($value)){
            $value = [$value];
        }
        return $builder->whereIn("type", $value);
    }
}
