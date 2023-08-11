<?php
namespace App\Api\Services;

use App\Api\Repositories\Events\EventsRepository;
use App\Api\Repositories\Events\EventCategoryRepository;
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
    public function create_event_operation(int $user_id, array $params){
        // 解析分类
        $category = (new EventCategoryRepository())->use_id_get_one_data($params['category_id']);
        $one_level_category_id = $category->parent_id;
        $two_level_category_id = $category->id;
        // 创建活动
        $res = (new EventsRepository())->create_data($user_id, $params['event_type'], $params['title'], $params['sex_limit'], $params['charge_type'], $params['award_content'], $params['site_address'], $params['site_longitude'], $params['site_latitude'], $params['start_time'], $params['end_time'], $one_level_category_id, $two_level_category_id, $params['require_content'], $params['image'], $params['video'], $params['service_phone'], $params['information_of_registration_key']);
        return $res;
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
        if($search['title'] != ''){
            $where['title'] = $search['title'];
        }
        if($search['sex'] != ''){
            $where['sex'] = $search['sex'];
        }
        if($search['category_id'] != ''){
            $where['oneLevelCategoryId'] = $search['category_id'];
        }
        if($search['date'] != ''){
            //TODO
        }
        // 获取数据并整理排序
        $list = (new EventsRepository())->use_search_get_list($where);
        $data = [];
        foreach($ids as $id){
            foreach($list as $v){
                if($v->id == $id){
                    $v->distance = $withdist[$id];
                    if(date("Y-m-d", strtotime($v->start_time)) == date("Y-m-d", strtotime($v->end_time))){
                        // 一日活动
                        $v->time = date("H:i", strtotime($v->start_time)) . '-' . date("H:i", strtotime($v->end_time)) . ' ' . date("m.d", strtotime($v->start_time));
                    }else{
                        // 非一日活动
                        $v->time = date("m.d H:i", strtotime($v->start_time)) . '-' . date("m.d H:i", strtotime($v->end_time));
                    }
                    $v->user_avatar = $v->user->avatar;
                    $v->user_nickname = $v->user->nickname;
                    unset($v->start_time, $v->end_time, $v->user);
                    $data[] = $v;
                }
            }
        }
        return $data;
    }
}