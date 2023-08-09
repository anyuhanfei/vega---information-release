<?php

namespace App\Admin\Repositories\Article;

use App\Models\Article\ArticleCategory as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class ArticleCategory extends EloquentRepository{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;

    public function get_all_data(){
        return $this->eloquentClass::all()->pluck('name', 'id');
    }

    public function use_id_get_name(int $id){
        return $this->eloquentClass::where('id', $id)->value('name');
    }

}
