<?php

namespace App\Admin\Repositories\User;

use App\Models\User\UserTags as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class UserTag extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
