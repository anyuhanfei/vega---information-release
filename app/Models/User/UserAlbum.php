<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\BaseFilter;

/**
 * 会员影集
 */
class UserAlbum extends Model{
    use BaseFilter;

    public $timestamps = false;
    protected $guarded = [];
    protected $table = "user_album";

    public function user(){
        return $this->hasOne(\App\Models\User\Users::class, 'id', 'user_id');
    }

    public function scopeId(Builder $builder, int $value){
        return $builder->where("id", $value);
    }

    public function scopeUserId(Builder $builder, int $value){
        return $builder->where("user_id", $value);
    }

    public function scopeVideo(Builder $builder, string $value){
        return $builder->where("video", $value);
    }
}
