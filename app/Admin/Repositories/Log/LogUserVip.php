<?php

namespace App\Admin\Repositories\Log;

use App\Models\Log\LogUserVip as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class LogUserVip extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
