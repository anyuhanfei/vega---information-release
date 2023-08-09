<?php

namespace App\Admin\Controllers\Sys;

use App\Admin\Repositories\Sys\SysImg;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class SysImgController extends AdminController{

    protected function grid(){
        return Grid::make((new SysImg())->model()->orderby('id', 'desc'), function (Grid $grid) {
            // $grid->column('id')->sortable();
            $grid->column('image_path', '图片')->display(function(){
                $image_path = config('app.url') . '/uploads/admin/' . $this->image_path;
                return '<img data-action="preview-img" src="' . $image_path . '" style="max-width:60px;max-height:60px;cursor:pointer" class="img img-thumbnail">';
            });
            $grid->column('image_path1', '图片路径')->display(function(){
                $image_path = config('app.url') . '/uploads/admin/' . $this->image_path;
                return "{$image_path}<i style='margin-left: 8px; font-size:16px;' title='复制' class='feather icon-copy' onclick='navigator.clipboard.writeText(\"{$image_path}\");layer.msg(\"复制成功\")'></i>";
            });
            $grid->disableEditButton();
            $grid->disableViewButton();
        });
    }

    protected function form(){
        return Form::make(new SysImg(), function (Form $form) {
            $form->display('id');
            $form->image('image_path')->disk('admin')->move('img')->autoUpload()->override();
            $form->disableEditingCheck();
            $form->disableViewCheck();
        });
    }
}
