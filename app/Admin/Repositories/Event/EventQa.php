<?php

namespace App\Admin\Repositories\Event;

use App\Models\Event\EventQa as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class EventQa extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
