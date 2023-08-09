<?php
namespace App\Api\Tools;

use Qiniu\Auth;
use Qiniu\Config;
use Qiniu\Storage\ArgusManager;
use zgldh\QiniuStorage\QiniuStorage;

class FileUploadTool{
    protected $allowed_ext = ["png", "jpg", "gif", 'jpeg', 'mp4', 'mp3'];
    protected $disk;
    protected $directory;
    protected $accessKey;
    protected $secretKey;
    protected $qiniu_doman;
    protected $qiniu_censor_image;

    public function __construct(){
        $this->accessKey = config('filesystems.disks.qiniu.access_key');
        $this->secretKey = config('filesystems.disks.qiniu.secret_key');
        $this->qiniu_doman = config('filesystems.disks.qiniu.domains.default');
        $this->qiniu_censor_image = config("filesystems.disks.qiniu.censor_image");
    }

    /**
     * 上传图片
     *
     * @param resource $file 文件资源对象
     * @return void
     */
    public function upload_file($file){
        $this->disk = config('filesystems.default');
        $this->directory = 'uploads/images/' . date('Y-m', time()) . '/';
        $upload_function = 'upload2' . $this->disk;
        $filename = $this->$upload_function($file);
        if($this->qiniu_censor_image){
            $this->qiniu_图片鉴黄审核($filename);
        }
        return $filename;
    }

    /**
     * 将图片上传到本地
     *
     * @param resource $file 文件资源对象
     * @return void
     */
    private function upload2local($file){
        $file_data = $this->rename($file);
        $file->move($this->directory, $file_data['file_name']);
        return $file_data['full_path_file_name'];
    }

    /**
     * 将图片上传到七牛云
     *
     * @param resource $file 文件资源对象
     * @return void
     */
    private function upload2qiniu($file){
        $disk = QiniuStorage::disk('qiniu');
        $file_data = $this->rename($file);
        $bool = $disk->put($file_data['directory_file_name'], file_get_contents($file->getRealPath()));
        if($bool){
            // $path = 'http://' . $this->qiniu_doman . '/' . $file_data['directory_file_name'];
            $path = $disk->downloadUrl($file_data['directory_file_name']);
            return $path;
        }
        return error('上传失败');
    }

    /**
     * 重命名文件并返回保存路径和本地完整访问路径
     *
     * @param resource $file
     * @return void
     */
    private function rename($file){
        if(!in_array($file->getClientOriginalExtension(), $this->allowed_ext)){
            throwBusinessException('不支持上传此类型文件');
        }
        $file_name = md5($file->getClientOriginalName().time().rand()).'.'.$file->getClientOriginalExtension();
        $directory_file_name = $this->directory . $file_name;
        $full_path_file_name = config('app.url') . '/' . $directory_file_name;
        return [
            'file_name'=> $file_name,
            'directory_file_name'=> $directory_file_name,
            'full_path_file_name'=> $full_path_file_name
        ];
    }

    public function qiniu_图片鉴黄审核(string $image_url){
        $auth = new Auth($this->accessKey, $this->secretKey);
        $config = new Config();
        $argusManager = new ArgusManager($auth, $config);
        $body = '{
            "data":{
                "uri":"' . $image_url . '"
            },
            "params":{
                "scenes":[
                    "pulp"
                ]
            }
        }';
        list($ret, $err) = $argusManager->censorImage($body);
        if($err != null){
            throwBusinessException($err);
        }
        if($ret['code'] != 200){
            throwBusinessException($ret['message']);
        }
        if($ret['result']['suggestion'] != 'pass'){
            throwBusinessException('上传的图片未通过审核');
        }
        return true;
    }
}