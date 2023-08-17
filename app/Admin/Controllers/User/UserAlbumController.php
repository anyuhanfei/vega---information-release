<?php

namespace App\Admin\Controllers\User;

use App\Admin\Repositories\User\UserAlbum;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class UserAlbumController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new UserAlbum(['user']), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('user_id', '会员信息')->width('200px')->display(function(){
                try{
                    return '<img data-action="preview-img" src="' . $this->user->avatar . '" style="max-width:60px;cursor:pointer;float:left;" class="img img-thumbnail"><p style="padding-top:3px;">&nbsp;ID: ' . $this->user_id . '</p><p >&nbsp;昵称: ' . $this->user->nickname . '</p>';
                }catch(\Exception $e){
                    return "账号已注销";
                }
            });
            $grid->column('type')->display(function(){
                return $this->video == '' ? '图片' : "视频";
            });
            $grid->column('image', '图片/视频封面图')->image('', 60, 60);
            $grid->column('title');
            $grid->disableCreateButton();
            $grid->selector(function (Grid\Tools\Selector $selector) {
                $selector->select('video', '类型', ['1'=> '图片', '2'=> '视频'], function($query, $value){
                    if($value[0] == '1'){
                        $query->where("video", '=', '');
                    }else{
                        $query->where("video", '<>', '');
                    }
                });
            });
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
                $filter->equal("user_id");
                $filter->like("user.nickname", "报名人昵称");
                $filter->like("user.phone", '会员手机号');
            });
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
        return Show::make($id, new UserAlbum(['user']), function (Show $show) {
            $grid->model()->orderBy('id', 'desc');
            $show->field('id');
            $show->field('user_id');
            $show->field('user.nickname', '会员昵称');
            $show->field('type')->as(function(){
                return $this->video == '' ? '图片' : "视频";
            });
            $show->field('image', '图片/封面图')->image("", 160, 160);
            $show->field('title');
            $show->field('video')->as(function(){
                if($this->video != ''){
                    return '<video width="420" height="240" controls><source src="' . $this->video . '" type="video/mp4"></video>';
                }
            })->unescape();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new UserAlbum(), function (Form $form) {
            $form->display('id');
            $form->display('user_id');
            if($form->model()->video == ''){
                $form->image('image')->autoUpload()->uniqueName()->saveFullUrl()->required()->removable(false)->retainable();
            }else{
                $form->text('title');
                $form->image('image', '视频封面图')->autoUpload()->uniqueName()->saveFullUrl()->required()->removable(false)->retainable();
                $form->file('video')->autoUpload()->uniqueName()->saveFullUrl()->required()->removable(false)->retainable();
            }
        });
    }
}
