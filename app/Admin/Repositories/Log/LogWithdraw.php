<?php

namespace App\Admin\Repositories\Log;

use App\Models\Log\LogWithdraw as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class LogWithdraw extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
