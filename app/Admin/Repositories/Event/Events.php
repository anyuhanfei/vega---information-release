<?php

namespace App\Admin\Repositories\Event;

use App\Models\Event\Events as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class Events extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
