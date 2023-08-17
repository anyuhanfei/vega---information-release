<?php

namespace App\Admin\Repositories\Event;

use App\Models\Event\EventOrders as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class EventOrder extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
