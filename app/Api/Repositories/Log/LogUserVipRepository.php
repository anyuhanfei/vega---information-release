<?php
namespace App\Api\Repositories\Log;

use App\Models\Log\LogUserVip as Model;

class LogUserVipRepository{
    protected $eloquentClass = Model::class;

    /**
     * 创建会员vip
     *
     * @param integer $user_id
     * @param string $vip_name
     * @param integer $day_number
     * @param string $start_time
     * @return void
     */
    public function create_data(int $user_id, string $vip_name, int $day_number, string $start_time){
        $end_time = date("Y-m-d H:i:s", strtotime($start_time) + 86400 * $day_number);
        return $this->eloquentClass::create([
            'user_id'=> $user_id,
            'vip_name'=> $vip_name,
            'day_number'=> $day_number,
            'start_time'=> $start_time,
            'end_time'=> $end_time,
        ]);
    }
}