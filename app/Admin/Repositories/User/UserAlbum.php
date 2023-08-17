<?php

namespace App\Admin\Repositories\User;

use App\Models\User\UserAlbum as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class UserAlbum extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
