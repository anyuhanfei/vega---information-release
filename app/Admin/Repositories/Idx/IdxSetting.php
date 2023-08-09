<?php

namespace App\Admin\Repositories\Idx;

use App\Models\Idx\IdxSetting as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class IdxSetting extends EloquentRepository{
    protected $eloquentClass = Model::class;

    public function get_type_page_attribute(string $type){
        return $this->eloquentClass::type_page_attribute()[$type];
    }

    public function get_type_fields(string $type){
        $function_name = $type . '_fields';
        return $this->eloquentClass::$function_name();
    }
}
