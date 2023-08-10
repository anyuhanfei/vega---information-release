<?php

namespace App\Admin\Repositories\Event;

use App\Models\Event\EventCategory as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class EventCategory extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
