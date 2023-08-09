<?php
namespace App\Api\Rules\Sys;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;

use App\Models\Sys\SysBanner;

class BannerSiteRule implements Rule, DataAwareRule{
    protected $data = [];

    public function setData($data){
        $this->data = $data;
        return $this;
    }

    public function passes($attribute, $value){
        if($value == '' || $value == null){
            return true;
        }else{
            $site_array = SysBanner::site_array();
            return in_array($value, $site_array);
        }
    }

    public function message(){
        return "请传入正确的位置参数";
    }
}