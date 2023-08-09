<?php

namespace App\Admin\Controllers\Sys;

use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Widgets\Card;

use App\Admin\Controllers\BaseController;
use App\Admin\Repositories\Sys\SysAd;
use App\Api\Repositories\Sys\SysAdRepository;

class SysAdController extends BaseController{
    protected int $id;
    protected int $parent_id;
    // protected string $title;
    protected string $type;
    protected string $value;
    protected string $content;
    protected string $image;
    protected int $update_allowed;
    protected int $delete_allowed;

    protected function grid(){
        return Grid::make(new SysAd(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->title->tree(true, false)->width('25%')->setAttributes(['style'=> 'word-break:break-all;']);
            $grid->column('value')->width('25%')->setAttributes(['style'=> 'word-break:break-all;']);
            $grid->column('image')->image('', 60, 60)->width('15%');
            $grid->column('content')->width('15%')->display('')->modal(function ($modal) {
                $modal->title($this->title);
                $this->content == null ? $modal->icon('feather ') : $modal->icon('feather icon-eye');
                $card = new Card(null, $this->content);
                return "<div style='padding:10px 10px 0'>$card</div>";
            });

            $grid->disableFilterButton();
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

            });
            $grid->disableRowSelector();
        });
    }

    protected function form(){
        return Form::make(new SysAd(), function (Form $form) {
            $form->tools(function (Form\Tools $tools) {
                $tools->disableView();
                $tools->disableDelete();
            });
            $form->display('id');
            $form->text('title')->required();
            $form->select('parent_id')
                ->when('!=', '', function(Form $form){
                    $form->select("type", '内容类型')->options(['文字'=> '文字', '图片'=> '图片', '富文本'=> '富文本'])->when('文字', function(Form $form){
                        $form->text('value');
                    })->when('图片', function(Form $form){
                        $form->image('image')->autoUpload()->uniqueName()->saveFullUrl()->removable(false)->retainable();
                    })->when("富文本", function(Form $form){
                        $form->editor('content')->height('600')->disk(config('admin.upload_disk'));
                    });
                })
                ->options((new SysAd())->get_parent_list())
                ->help('如果添加的是广告位，则不要选择广告位');
            if(config('admin.developer_mode')){
                $form->switch("delete_allowed", '是否允许删除')->value(1);
                $form->switch("update_allowed", '是否允许修改')->value(1);
            }
            $form->footer(function ($footer) {
                $footer->disableViewCheck();
            });
            $form->saving(function (Form $form) {
                $form->parent_id = $form->parent_id ?? 0;
                $form->value = $form->value ?? '';
                $form->image = $form->image ?? '';
                $form->content = $form->content ?? '';
                if($form->parent_id != 0){
                    $value = $form->value;
                    $image = $form->image;
                    $content = $form->content;
                    if($value == '' && $image == '' && $content == ''){
                        return $form->response()->error('值、图片、内容请至少填写一项');
                    }
                }
            });

            // 清除缓存
            $form->saved(function(Form $form, $result){
                (new SysAdRepository())->del_cache($form->id ?? $form->model()->id ?? $result);
            });
            $form->deleted(function(Form $form, $result){
                $data_id = $form->model()->toArray()[0]['id'];
                (new SysAd())->del_cache($data_id);
            });

            $form->disableViewCheck();
            $form->disableEditingCheck();
            $form->disableCreatingCheck();
        });
    }
}
