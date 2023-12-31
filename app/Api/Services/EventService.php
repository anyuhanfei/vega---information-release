<?php
namespace App\Api\Services;

use App\Api\Repositories\Events\EventsRepository;
use App\Api\Repositories\Events\EventCategoryRepository;
use App\Api\Repositories\Events\EventOrderRepository;
use App\Api\Repositories\Idx\IdxSettingRepository;
use App\Api\Repositories\Sys\SysSettingRepository;
use App\Api\Repositories\User\UsersRepository;

class EventService{

    /**
     * 获取活动分类
     *
     * @param integer $parent_id
     * @return void
     */
    public function get_event_category(int $parent_id = 0){
        if($parent_id != 0){
            $data = (new EventCategoryRepository())->use_parent_id_get_children($parent_id);
        }else{
            $data = (new EventCategoryRepository())->use_get_tree_data();
        }
        return $data;
    }

    /**
     * 创建活动
     *
     * @param integer $user_id
     * @param array $params
     * @return void
     */
    public function create_event_operation(int $user_id, string $pay_method, array $params){
        // 解析分类
        $category = (new EventCategoryRepository())->use_id_get_one_data($params['category_id']);
        $one_level_category_id = $category->parent_id;
        $two_level_category_id = $category->id;
        // 创建活动
        $data = (new EventsRepository())->create_data($user_id, $params['event_type'], $params['title'], $params['sex_limit'], $params['charge_type'], $params['award_content'], $params['site_address'], $params['site_longitude'], $params['site_latitude'], $params['start_time'], $params['end_time'], $one_level_category_id, $two_level_category_id, $params['require_content'], $params['image'], $params['video'], $params['service_phone'], $params['information_of_registration_key']);
        $price = (new SysService())->get_release_price($user_id);
        // if($price > 0){
        //     // 支付
        //     $pay_data = (new PayService())->pay($pay_method, $user_id, $price, $data->id, '发布活动', '发布活动');
        // }else{
        //     $pay_data = (new PayService())->发布活动($data->id, $price);
        // }
        // 测试阶段直接支付成功
        $pay_data = (new PayService())->发布活动($data->id, $price);
        return $pay_data;
    }

    public function update_event_operation(int $user_id, int $event_id, array $params){
        // 解析分类
        $category = (new EventCategoryRepository())->use_id_get_one_data($params['category_id']);
        $one_level_category_id = $category->parent_id;
        $two_level_category_id = $category->id;
        // 修改活动信息
        $data = (new EventsRepository())->update_data($event_id, $params['event_type'], $params['title'], $params['sex_limit'], $params['charge_type'], $params['award_content'], $params['site_address'], $params['site_longitude'], $params['site_latitude'], $params['start_time'], $params['end_time'], $one_level_category_id, $two_level_category_id, $params['require_content'], $params['image'], $params['video'], $params['service_phone'], $params['information_of_registration_key']);
        return true;
    }

    /**
     * 获取活动列表
     *
     * @param integer $user_id
     * @param [type] $page
     * @param [type] $limit
     * @param [type] $search
     * @return void
     */
    public function get_event_list(int $user_id, int $page, int $limit, array $search){
        // 根据经纬度获取附近的活动（如果没有经纬度，则获取最新的活动）
        if($search['longitude'] == 0){
            $coordinate = (new UsersRepository())->get_user_coordinate($user_id);
            $search['longitude'] = $coordinate['longitude'];
            $search['latitude'] = $coordinate['latitude'];
            if($search['longitude'] != 0){
                $withdist = [];
                $ids = [];
                foreach((new EventsRepository())->get_nearby_event_ids($search['longitude'], $search['latitude']) as $v){
                    $withdist[$v[0]] = $v[1];
                    $ids[] = $v[0];
                }
                try{
                    $ids = array_chunk($ids, $limit)[$page - 1];
                }catch(\Throwable $th){
                    $ids = [];
                }
            }else{
                $ids = (new EventsRepository())->get_new_event_ids($page, $limit);
                $withdist = array_fill_keys($ids, 0);
            }
        }
        // 整合全部条件
        $where['id'] = $ids;
        $where['status'] = [20, 30];
        if(!empty($search['title']) && $search['title'] != ''){
            $where['title'] = $search['title'];
        }
        if(!empty($search['sex']) && $search['sex'] != ''){
            $where['sex'] = $search['sex'];
        }
        if(!empty($search['category_id']) && $search['category_id'] != ''){
            $where['twoLevelCategoryId'] = $search['category_id'];
        }
        if(!empty($search['date']) && $search['date'] != ''){
            $where['statusTime'] = $search['date'];
        }
        // 获取数据并整理排序
        $list = (new EventsRepository())->use_search_get_list($where);
        $data = [];
        foreach($ids as $id){
            foreach($list as $v){
                if($v->id == $id){
                    $v->distance = $withdist[$id];
                    $v->time = (new EventsRepository())->整理时间数据($v->start_time, $v->end_time);
                    $v->publish_avatar = $v->user->avatar;
                    $v->publish_nickname = $v->user->nickname;
                    unset($v->start_time, $v->end_time, $v->user);
                    $data[] = $v;
                    [$v->user_number, $v->user_avatars] = (new EventOrderRepository())->获取活动的报名数据($v->id);
                }
            }
        }
        return $data;
    }

    public function get_user_event_list(int $user_id, int $page, int $limit, int $other_id = 0, array $status = []){
        if($other_id != 0){  // 查看他人的活动列表
            $where['userId'] = $other_id;
        }else{  // 查看自己的活动列表
            $where['userId'] = $user_id;
        }
        $where['status'] = $status;
        $list = (new EventsRepository())->use_search_get_list($where, $page, $limit);
        $coordinate = (new UsersRepository())->get_user_coordinate($user_id);
        $data = [];
        foreach($list as $v){
            $v->distance = get_distance($coordinate['longitude'], $coordinate['latitude'], $v->site_longitude, $v->site_latitude);
            $v->time = (new EventsRepository())->整理时间数据($v->start_time, $v->end_time);
            $v->user_avatar = $v->user->avatar;
            $v->user_nickname = $v->user->nickname;
            unset($v->start_time, $v->end_time, $v->user, $v->site_longitude, $v->site_latitude);
            [$v->user_number, $v->user_avatars] = (new EventOrderRepository())->获取活动的报名数据($v->id);
            $v->status = (new EventsRepository())->status_array()[$v->status];
            $data[] = $v;
        }
        return $data;
    }

    public function get_event_detail(int $user_id, int $event_id){
        $event = (new EventsRepository())->use_id_get_one_data($event_id);
        if(!$event || $event->status < 20){
            throwBusinessException("活动不存在或已下架");
        }
        $coordinate = (new UsersRepository())->get_user_coordinate($user_id);
        $user = (new UsersRepository())->use_id_get_one_data($user_id);
        // 是否可报名
        $is_apply = $event->sex_limit == '全部' ? true : ($event->sex_limit == $user->sex ? true : false);
        // 是否收费
        switch($event->charge_type){
            case '收费':
                $price = (new SysSettingRepository())->use_id_get_value(29);
                break;
            case '男收费':
                $price = (new SysSettingRepository())->use_id_get_value(30);
                break;
            case '女收费':
                $price = (new SysSettingRepository())->use_id_get_value(31);
                break;
            default:
                $price = 0;
                break;
        }
        return [
            'id'=> $event->id,
            'publisher_id'=> $event->user_id,
            'publisher_avatar'=> $event->user->avatar,
            'publisher_nickname'=> $event->user->nickname,
            'image'=> $event->image,
            'video'=> $event->video,
            'title'=> $event->title,
            'time'=> (new EventsRepository())->整理时间数据($event->start_time, $event->end_time),
            'distance'=> get_distance($coordinate['longitude'], $coordinate['latitude'], $event->site_longitude, $event->site_latitude),
            'site_address'=> $event->site_address,
            'site_longitude'=> $event->site_longitude,
            'site_latitude'=> $event->site_latitude,
            'service_phone'=> $event->service_phone,
            'award_content'=> $event->award_content,
            'require_content'=> $event->require_content,
            'sex_limit'=> $event->sex_limit,
            'event_type'=> $event->event_type,
            'charge_type'=> $event->charge_type,
            'status'=> $event->status,
            'is_apply'=> $is_apply,
            'price'=> $price,
            'information_of_registration_key'=> (new IdxSettingRepository())->get_information_of_registration_key_details(comma_str_to_array($event->information_of_registration_key)),
        ];
    }

    public function get_user_event_detail(int $user_id, int $event_id){
        $event = (new EventsRepository())->use_id_get_one_data($event_id);
        if(!$event || $event->user_id != $user_id){
            throwBusinessException("活动不存在或已下架");
        }
        $event->one_level_category = $event->one_level_category;
        $event->two_level_category = $event->two_level_category;
        $event->information_of_registration_key = (new IdxSettingRepository())->get_information_of_registration_key_details(comma_str_to_array($event->information_of_registration_key));
        return $event;
    }

    /**
     * 取消会员操作
     *
     * @param integer $user_id
     * @param integer $event_id
     * @return void
     */
    public function user_event_cancel_operation(int $user_id, int $event_id){
        $event = (new EventsRepository())->use_id_get_one_data($event_id);
        if(!$event || $event->user_id != $user_id){
            throwBusinessException("活动不存在或已下架");
        }
        if($event->status == -1){
            throwBusinessException("活动已取消,请勿重复提交");
        }
        if($event->status > 20){
            throwBusinessException("当前活动已无法取消");
        }
        // 取消活动
        (new EventsRepository())->cancel_data($event_id);
        return true;
    }
}