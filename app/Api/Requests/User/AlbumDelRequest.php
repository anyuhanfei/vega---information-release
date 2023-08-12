<?php
namespace App\Api\Requests\User;

use Illuminate\Validation\Rule;

use App\Api\Requests\BaseRequest;


class AlbumDelRequest extends BaseRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'album_id'=> ['required', new \App\Api\Rules\User\UserAlbumVerify()],
        ];
    }

    public function messages(){
        return [
            'album_id.required'=> '请选择影集',
        ];
    }
}