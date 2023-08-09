<?php
namespace App\Api\Requests\Sys;

use Illuminate\Validation\Rule;

use App\Api\Requests\BaseRequest;

class BannerRequest extends BaseRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'site' => [new \App\Api\Rules\Sys\BannerSiteRule],
        ];
    }

    public function messages(){
        return [
        ];
    }
}