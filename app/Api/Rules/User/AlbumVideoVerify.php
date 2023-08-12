<?php
namespace App\Api\Rules\User;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;

use App\Api\Repositories\Idx\IdxSettingRepository;
use App\Api\Repositories\User\UserTagsRepository;

/**
 * 会员上传视频验证
 */
class AlbumVideoVerify implements Rule, DataAwareRule{
    protected $data = [];

    public function setData($data){
        $this->data = $data;
        return $this;
    }

    public function passes($attribute, $value){
        if($this->data['type'] == '视频'){
            if($this->data['video'] == '' || $this->data['video'] == null || $this->data['video'] == 0){
                return false;
            }
            if($this->data['title'] == '' || $this->data['title'] == null || $this->data['title'] == 0){
                return false;
            }
        }
        return true;
    }

    public function message(){
        return "请填写/上传所有信息";
    }
}