<?php
namespace App\Api\Repositories\Events;

use App\Models\Event\Events as Model;

class EventsRepository{
    protected $eloquentClass = Model::class;

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
}