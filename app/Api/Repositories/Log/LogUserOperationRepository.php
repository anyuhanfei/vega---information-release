<?php
namespace App\Api\Repositories\Log;

use App\Models\Log\LogUserOperation as Model;

class LogUserOperationRepository{
    protected $eloquentClass = Model::class;
}