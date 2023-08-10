<?php

namespace App\Admin\Controllers\Event;

use App\Admin\Repositories\Event\EventCategory;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventCategoryController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new EventCategory(), function (Grid $grid) {
            $grid->column('id')->sortable();
            // $grid->column('parent_id');
            $grid->column('name')->tree(true, false)->width('25%')->setAttributes(['style'=> 'word-break:break-all;']);
            $grid->column('icon')->image("", 60, 60);
            // $grid->column('created_at');
            // $grid->column('updated_at')->sortable();
        
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
        
            });
            $grid->disableViewButton();
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new EventCategory(), function (Show $show) {
            $show->field('id');
            $show->field('parent_id');
            $show->field('name');
            $show->field('icon');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(){
        return Form::make(new EventCategory(), function (Form $form) {
            $form->display('id');
            $form->text('name')->required();
            $form->select('parent_id', '上级分类')->options("api/category")->when("=", '', function(Form $form){
                $form->image('icon')->autoUpload()->uniqueName()->saveFullUrl()->removable(false)->retainable();
            });
            if($form->isEditing() && $form->model()->parent_id == 0){
                $form->image('icon')->autoUpload()->uniqueName()->saveFullUrl()->removable(false)->retainable();

            }
            $form->saving(function(Form $form){
                $form->parent_id = $form->parent_id ?? 0;
                $form->icon = $form->icon ?? '';
                if($form->parent_id == 0 && $form->icon == ''){
                    return $form->response()->error('请上传图标');
                    // $form->responseValidationMessages('icon', '请选择图标');
                }
            });
            $form->disableViewButton();
        });
    }

    public function api_category(Request $request){
        $q = $request->get('q');
        return (new EventCategory())->model()->where('parent_id', 0)->where("name", 'like', '%'.$q.'%')->get(['id', DB::raw("name as text")]);
    }
}
