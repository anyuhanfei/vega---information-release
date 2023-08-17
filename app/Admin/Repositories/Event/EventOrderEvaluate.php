<?php

namespace App\Admin\Repositories\Event;

use App\Models\Event\EventOrderEvaluates as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class EventOrderEvaluate extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;

    public function score_text(int $score){
        return (new $this->eloquentClass())->score_array()[$score];
    }
}
