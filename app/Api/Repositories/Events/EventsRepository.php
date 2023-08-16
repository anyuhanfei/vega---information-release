<?php
namespace App\Api\Repositories\Events;

use App\Models\Event\Events as Model;
use Illuminate\Support\Facades\Redis;

class EventsRepository{
    protected $eloquentClass = Model::class;

    public function status_array(){
        return (new $this->eloquentClass)->status_array();
    }

    public function create_data(int $user_id, string $event_type, string $title, string $sex_limit, string $charge_type, string $award_content, string $site_address, string $site_longitude, string $site_latitude, string $start_time, string $end_time, int $one_level_category_id, int $two_level_category_id, string $require_content, string $image, string $video, string $service_phone, string $information_of_registration_key){
        return $this->eloquentClass::create([
            'user_id' => $user_id,
            'event_type' => $event_type,
            'title' => $title,
            'sex_limit' => $sex_limit,
            'charge_type' => $charge_type,
            'award_content' => $award_content,
            'site_address' => $site_address,
            'site_longitude' => $site_longitude,
            'site_latitude' => $site_latitude,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'one_level_category_id' => $one_level_category_id,
            'two_level_category_id' => $two_level_category_id,
            'require_content' => $require_content,
            'image' => $image,
            'video' => $video,
            'service_phone' => $service_phone,
            'information_of_registration_key' => $information_of_registration_key,
            'status'=> 0
        ]);
    }

    public function update_data(int $event_id, string $event_type, string $title, string $sex_limit, string $charge_type, string $award_content, string $site_address, string $site_longitude, string $site_latitude, string $start_time, string $end_time, int $one_level_category_id, int $two_level_category_id, string $require_content, string $image, string $video, string $service_phone, string $information_of_registration_key){
        return $this->eloquentClass::id($event_id)->update([
            'event_type' => $event_type,
            'title' => $title,
            'sex_limit' => $sex_limit,
            'charge_type' => $charge_type,
            'award_content' => $award_content,
            'site_address' => $site_address,
            'site_longitude' => $site_longitude,
            'site_latitude' => $site_latitude,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'one_level_category_id' => $one_level_category_id,
            'two_level_category_id' => $two_level_category_id,
            'require_content' => $require_content,
            'image' => $image,
            'video' => $video,
            'service_phone' => $service_phone,
            'information_of_registration_key' => $information_of_registration_key,
            'status'=> 0
        ]);
    }

    public function add_geo(int $event_id, float|string $longitude, float|string $latitude){
        Redis::geoadd("event_geo", $longitude, $latitude, $event_id);
    }

    /**
     * 获取附近的活动
     *
     * @param float|string $longitude
     * @param float|string $latitude
     * @return void
     */
    public function get_nearby_event_ids(float|string $longitude, float|string $latitude){
        return Redis::georadius("event_geo", $longitude, $latitude, 100, 'km', ['ASC', 'withdist']);
    }

    /**
     * 获取最新的活动id
     *
     * @param integer $page
     * @param integer $limit
     * @return void
     */
    public function get_new_event_ids(int $page, int $limit){
        return $this->eloquentClass::page($page, $limit)->orderBy('id', 'desc')->pluck('id')->toArray();
    }

    /**
     * 通过搜索条件获取活动信息
     *
     * @param array $where
     * @return void
     */
    public function use_search_get_list(array $where, int $page = 1, int $limit = 100){
        return $this->eloquentClass::with(['user'])->apply($where)->select(['id', "title", 'image', 'start_time', 'end_time', 'user_id', 'status', 'site_longitude', 'site_latitude', 'reject_cause'])->get();
    }

    /**
     * 根据id获取数据
     *
     * @param integer $id
     * @return void
     */
    public function use_id_get_one_data(int $id){
        return $this->eloquentClass::id($id)->first();
    }

    /**
     * 修改指定活动的状态为已支付状态，并且存储支付金额
     *
     * @param integer $id
     * @param float $money
     * @return void
     */
    public function use_id_update_status_10(int $id, float $money){
        return $this->eloquentClass::id($id)->update([
            'status'=> 10,
            'pay_price'=> $money
        ]);
    }

    public function cancel_data(int $id){
        // 取消活动
        $this->eloquentClass::id($id)->update([
            'status'=> -1,
        ]);
        // TODO::退款
        // 取消订单
        (new EventOrderRepository())->集体取消订单($id);
    }


    public function 整理时间数据(string $start_date, string $end_date){
        $start_time = strtotime($start_date);
        $end_time = strtotime($end_date);
        if(date("Y-m-d", $start_time) == date("Y-m-d", $end_time)){
            // 一日活动
            return date("H:i", $start_time) . '-' . date("H:i", $end_time) . ' ' . date("m.d", $start_time);
        }else{
            // 非一日活动
            return date("m.d H:i", $start_time) . '-' . date("m.d H:i", $end_time);
        }
    }

    /**
     * 修改活动状态
     *
     * @param integer $event_id
     * @param integer $status
     * @return void
     */
    public function update_event_status(int $event_id, int $status){
        return $this->eloquentClass::id($event_id)->update([
            'status'=> $status,
        ]);
    }
}