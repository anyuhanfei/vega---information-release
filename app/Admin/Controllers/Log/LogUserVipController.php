<?php

namespace App\Admin\Controllers\Log;

use App\Admin\Repositories\Idx\IdxSetting;
use App\Admin\Repositories\Log\LogUserVip;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class LogUserVipController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new LogUserVip(), function (Grid $grid) {
            $grid->column('id')->sortable()->width("100px");
            $grid->model()->orderBy('id', 'desc');
            $grid->column('user_id', '会员信息')->display(function(){
                try{
                    return '<img data-action="preview-img" src="' . $this->user->avatar . '" style="max-width:60px;cursor:pointer;float:left;" class="img img-thumbnail"><p style="padding-top:3px;">&nbsp;ID: ' . $this->user_id . '</p><p >&nbsp;昵称: ' . $this->user->nickname . '</p>';
                }catch(\Exception $e){
                    return "账号已注销";
                }
            });
            $grid->column('vip_name')->width("150px");
            $grid->column('day_number')->width("100px")->display(function(){
                return $this->day_number . '天';
            });
            $grid->column('start_time')->width("200px");
            $grid->column('end_time')->width("200px");
            $grid->column('%', '进度')->width("200px")->display(function(){
                $start_time = strtotime($this->start_time);
                $end_time = strtotime($this->end_time);
                if(time() < $start_time){
                    return 0;
                }elseif(time() > $end_time){
                    return 100;
                }else{
                    return (time() - $start_time) / ($end_time - time());
                }
            })->progressBar('success');
            // $grid->column('status');
            // $grid->column('created_at');
            // $grid->column('updated_at')->sortable();
            $grid->disableCreateButton();
            $grid->disableDeleteButton();
            $grid->disableEditButton();
            $grid->disableViewButton();
            $grid->disableBatchDelete();
            $grid->selector(function (Grid\Tools\Selector $selector) {
                $selector->select('vip_name', 'VIP', (new IdxSetting())->model()->where("type", 'vip')->pluck("value0", 'value0'));
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
        return Show::make($id, new LogUserVip(), function (Show $show) {
            $show->field('id');
            $show->field('user_id');
            $show->field('vip_name');
            $show->field('day_number');
            $show->field('start_time');
            $show->field('end_time');
            $show->field('status');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new LogUserVip(), function (Form $form) {
            $form->display('id');
            $form->text('user_id');
            $form->text('vip_name');
            $form->text('day_number');
            $form->text('start_time');
            $form->text('end_time');
            $form->text('status');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
