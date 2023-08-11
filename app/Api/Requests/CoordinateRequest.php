<?php
namespace App\Api\Requests;


class CoordinateRequest extends BaseRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'longitude' => ['required', 'min:-180', 'max:180'],
            'latitude' => ['required', 'min:-90', 'max:90'],
        ];
    }

    public function messages(){
        return [
            'longitude.required'=> '请指定经度',
            'longitude.min'=> '请指定正确的经度',
            'longitude.max'=> '请指定正确的经度',
            'latitude.required'=> '请指定纬度',
            'latitude.min'=> '请指定正确的纬度',
            'latitude.max'=> '请指定正确的纬度',
        ];
    }
}