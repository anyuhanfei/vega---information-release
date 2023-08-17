<?php

namespace App\Admin\Repositories\Log;

use App\Models\Log\LogFeedback as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class LogFeedback extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
