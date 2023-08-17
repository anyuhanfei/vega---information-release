<?php
namespace App\Api\Services;

use App\Api\Repositories\Sys\SysBannerRepository;
use App\Api\Repositories\Sys\SysNoticeRepository;
use App\Api\Repositories\Sys\SysAdRepository;
use App\Api\Repositories\Article\ArticleRepository;
use App\Api\Repositories\Article\ArticleCategoryRepository;
use App\Api\Repositories\Article\ArticleTagRepository;
use App\Api\Repositories\Idx\IdxSettingRepository;
use App\Api\Repositories\Sys\SysSettingRepository;
use App\Api\Repositories\User\UsersRepository;

class SysService{
    /**
     * 获取全部的轮播图
     *
     * @return void
     */
    public function get_banners(string $site = '首页'){
        return (new SysBannerRepository())->获取指定位置数据($site);
    }

    /**
     * 获取公告
     * 根据系统设置返回指定公共
     *
     * @param integer $id
     * @return void 单条的公告信息
     */
    public function get_notice(int $user_id = 0, int $id = 0){
        $SysNoticeRepository = new SysNoticeRepository();
        switch(config('admin.notice.type')){
            case '单条文字':
                $data = $SysNoticeRepository->get_first_data();
                unset($data->content, $data->updated_at, $data->deleted_at);
                break;
            case "多条文字":
                if($id == 0){
                    throwBusinessException('当前公告模式设置为多条，请使用 get_notice_list 接口或传递 id 参数!');
                }
                $data = $SysNoticeRepository->use_id_get_one_data($id);
                unset($data->content, $data->updated_at, $data->deleted_at);
                break;
            case "单条富文本":
                $data = $SysNoticeRepository->get_first_data($id);
                unset($data->updated_at, $data->deleted_at);
                break;
            case "多条富文本":
                if($id == 0){
                    throwBusinessException('当前公告模式设置为多条，请使用 get_notice_list 接口或传递 id 参数!');
                }
                $data = $SysNoticeRepository->use_id_get_one_data($id);
                unset($data->updated_at, $data->deleted_at);
                break;
            default:
                return [];
        }
        return $data;
    }

    /**
     * 获取公告列表
     *  只有多条文字货多条富文本才能访问；
     *  二者返回数据相同，富文本获取详情数据需要访问获取公告详情接口
     *
     * @param integer $page 页码
     * @param integer $limit 每页展示数据数量
     * @return void
     */
    public function get_notice_list(int $page = 1, int $limit = 10){
        $SysNoticeRepository = new SysNoticeRepository();
        $notice_type = config('admin.notice.type');
        if($notice_type != '多条富文本' && $notice_type != '多条文字'){
            throwBusinessException('当前公告模式设置为单条，请直接使用 get_notice 接口');
        }
        $data = $SysNoticeRepository->get_list($page, $limit);
        return $data;
    }

    /**
     * 返回指定广告位信息
     *
     * @param int $id
     * @return void
     */
    public function get_ad(int $id){
        $ad_type_array = ['文字'=> 'value', '图片'=> 'image', '富文本'=> 'content'];
        $data = (new SysAdRepository())->use_id_get_one_data($id);
        if($data->parend_id == 0){
            // 查到的数据是广告位，需要获取其下的广告
            $return_data = ['id'=> $data->id, 'title'=> $data->title, 'ad'=> []];
            foreach($data->children as $child){
                $value_key_name = $ad_type_array[$child->type];
                $return_data['ad'][] = [
                    'id'=> $child->id,
                    'title'=> $child->title,
                    'type'=> $value_key_name,
                    'value'=> $child->$value_key_name,
                ];
            }
        }else{
            // 广告，直接整理数据
            $value_key_name = $ad_type_array[$data->type];
            $return_data['ad'][] = [
                'id'=> $data->id,
                'title'=> $data->title,
                'type'=> $value_key_name,
                'value'=> $data->$value_key_name,
            ];
        }
        return $return_data;
    }

    /**
     * 获取文章分类列表
     *
     */
    public function get_article_category_list(){
        $ArticleCategoryRepository = new ArticleCategoryRepository();
        $data = $ArticleCategoryRepository->get_all_data();
        $return_data = [];
        foreach($data as &$v){
            $return_data[] = ['id'=> $v->id, 'name'=> $v->name, 'image'=> $v->image];
        }
        return $return_data;
    }

    /**
     * 获取文章列表
     *
     * @param integer $category_id
     * @param integer $page
     * @param integer $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get_article_list(int $category_id, int $page = 1, int $limit = 10){
        if($category_id != 0){
            $data = (new ArticleRepository())->use_category_get_list($category_id, $page, $limit);
        }else{
            $data = (new ArticleRepository())->get_list($page, $limit);
        }
        foreach($data as &$v){
            // 关键词，分类数据、标签数据
            $v->keyword = comma_str_to_array($v->keyword);
            $tag_ids = json_decode($v->tag_ids);
            $v->tags = (new ArticleTagRepository())->use_ids_get_datas($tag_ids);
            unset($v->category_id, $v->tag_ids);
        }
        return $data;
    }

    /**
     * 获取文章详情
     *
     * @param integer $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function get_article_detail(int $id){
        $data = (new ArticleRepository())->use_id_get_one_data($id);
        if(!$data){
            throwBusinessException('文章不存在');
        }
        return ['content'=> $data->content];
    }


    /**
     * 获取指定类型的项目设置
     * 其中，user_avatars 预设头像不能全部获取，业务需要随机获取4个，所以要特殊处理
     *
     * @param string $type
     * @return array
     */
    public function get_setting_list(string $type):array{
        $IdxSettingRepository = new IdxSettingRepository();
        switch($type){
            case 'user_avatars':
                $data = $this->get_setting_user_avatars_list();
                break;
            case "information_of_registration_key":
                $data = $this->get_setting_information_of_registration_key_list();
                break;
            default:
                $data = $IdxSettingRepository->use_type_get_datas($type);
                break;
        }
        return ['msg'=> $IdxSettingRepository->get_type_name($type), 'data'=> $data];
    }

    /**
     * 获取当前举办费用
     *
     * @param integer $user_id
     * @return void
     */
    public function get_release_price(int $user_id){
        // 获取基础费用
        $price = (new SysSettingRepository())->use_id_get_value(33);
        // 获取会员等级
        $user = (new UsersRepository())->use_id_get_one_data($user_id);
        // 获取等级折扣
        $vip_discount = 0;
        if($user->vip != ''){
            $vip = (new IdxSettingRepository())->use_vipname_get_one_data($user->vip);
            $vip_discount = $vip->value3;
        }
        // 返回费用
        return round($price * (1 - $vip_discount * 0.01), 2);
    }

    /**
     * 随机获取4个预设头像
     *
     * @return array
     */
    private function get_setting_user_avatars_list():array{
        $IdxSettingRepository = new IdxSettingRepository();
        $data = $IdxSettingRepository->random_get_user_avatars_list(4);
        return $data;
    }

    /**
     * 获取信息类型的列表
     *
     * @return array
     */
    private function get_setting_information_of_registration_key_list():array{
        $IdxSettingRepository = new IdxSettingRepository();
        $data = $IdxSettingRepository->get_information_of_registration_key_list();
        return $data;
    }
}