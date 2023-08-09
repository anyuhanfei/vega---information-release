<?php

namespace App\Admin\Repositories\Sys;

use App\Models\Sys\SysImg as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class SysImg extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
