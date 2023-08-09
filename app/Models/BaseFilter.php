<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

trait BaseFilter{

    public function scopeApply(Builder $builder, array $params){
        foreach($params as $field_name=> $value){
            if(method_exists($this, 'scope' . ucfirst($field_name))){
                call_user_func_array([$builder, $field_name], array_filter([$value]));
            }
        }
        return $builder;
    }

    public function scopePage(Builder $builder, int $page, int $limit){
        return $builder->offset(($page - 1) * $limit)->limit($limit);
    }
}