<?php
namespace App\Api\Repositories\Events;

use App\Models\Event\EventCategory as Model;

class EventCategoryRepository{
    protected $eloquentClass = Model::class;

    /**
     * 通过id获取一条数据
     *
     * @param integer $id
     * @return void
     */
    public function use_id_get_one_data(int $id){
        return $this->eloquentClass::id($id)->first();
    }

    /**
     * 获取活动分类的树状结构图
     *
     * @return void
     */
    public function use_get_tree_data(){
        $data = $this->eloquentClass::parentId(0)->get();
        foreach($data as &$v){
            unset($v->created_at, $v->deleted_at, $v->updated_at, $v->parent_id);
            foreach($v->children as &$c){
                unset($c->created_at, $c->deleted_at, $c->updated_at, $c->parent_id, $c->icon);
            }
        }
        return $data;
    }

    /**
     * 通过上级id获取下级分类
     *
     * @param integer $parent_id
     * @return void
     */
    public function use_parent_id_get_children(int $parent_id){
        $data = $this->eloquentClass::parentId($parent_id)->select(['id', 'name'])->get();
        return $data;
    }

}