<?php
namespace App\Api\Services;

use App\Api\Repositories\Events\EventsRepository;
use App\Api\Repositories\Events\EventCategoryRepository;


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
}