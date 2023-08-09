<?php

namespace App\Admin\Controllers\Article;

use Dcat\Admin\Form;
use Dcat\Admin\Grid;

use App\Admin\Controllers\BaseController;
use App\Admin\Repositories\Article\ArticleCategory;
use App\Api\Repositories\Article\ArticleCategoryRepository;


class ArticleCategoryController extends BaseController{
    protected int $id;
    protected string $name;
    protected string $image;
    protected int $delete_allowed;
    protected int $update_allowed;

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(){
        return Grid::make(new ArticleCategory(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('name');
            if(config('admin.article.category_image_show')){
                $grid->column('image')->image('', 40, 40);
            }

            $grid->disableViewButton();
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                if($this->delete_allowed == 0){
                    $actions->disableDelete();
                }
                if($this->update_allowed == 0){
                    $actions->disableEdit();
                }
            });

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
                $filter->equal('name');
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(){
        return Form::make(new ArticleCategory(), function (Form $form) {
            $form->tools(function (Form\Tools $tools) {
                $tools->disableView();
            });

            $form->display('id');
            $form->text('name')->required();
            if(config('admin.article.category_image_show')){
                $form->image('image')->autoUpload()->uniqueName()->saveFullUrl()->required()->removable(false)->retainable();
            }
            if(config('admin.developer_mode')){
                $form->switch("delete_allowed", '是否允许删除')->value(1);
                $form->switch("update_allowed", '是否允许修改')->value(1);
            }

            // 清除缓存
            $form->saved(function(Form $form, $result){
                (new ArticleCategoryRepository())->del_cache($form->id ?? $form->model()->id ?? $result);
            });
            $form->deleted(function(Form $form, $result){
                $data_id = $form->model()->toArray()[0]['id'];
                (new ArticleCategoryRepository())->del_cache($data_id);
            });

            $form->footer(function ($footer) {
                $footer->disableViewCheck();
            });
        });
    }
}
