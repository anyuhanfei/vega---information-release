<?php

namespace App\Admin\Controllers\Event;

use App\Admin\Repositories\Event\Events;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class EventsController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Events(['user', 'one_level_category', 'two_level_category']), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('user_id', '会员信息')->width('200px')->display(function(){
                return '<img data-action="preview-img" src="' . $this->user->avatar . '" style="max-width:60px;cursor:pointer;float:left;" class="img img-thumbnail"><p style="padding-top:3px;">&nbsp;ID: ' . $this->user_id . '</p><p >&nbsp;昵称: ' . $this->user->nickname . '</p>';
            });
            $grid->column('title', '活动信息')->width("400px")->display(function(){
                $sex_limit = $this->sex_limit == '全部' ? '' : ('<span class="label" style="background:#586cb1">限' . $this->sex_limit . '性</span>&nbsp;');
                return '<img data-action="preview-img" src="' . $this->image . '" style="max-width:140px;max-height:140px;cursor:pointer;float:left;" class="img img-thumbnail"><h5 style="padding-top:7px;">&nbsp;' . $this->title . '</h5><span class="label" style="background:#586cb1">' . $this->event_type . '</span>&nbsp;' . $sex_limit . '<span class="label" style="background:#586cb1">' . $this->one_level_category->name . '-' . $this->two_level_category->name . '</span>&nbsp;<span class="label" style="background:#586cb1">' . $this->charge_type . '</span>&nbsp;<br/><br/><span style="padding-top:14px;">需填写: ' . $this->information_of_registration_key . '</span>';
            });
            $grid->column('service_phone', '举办人手机号');
            $grid->column('site_address');
            $grid->column('start_time')->width("190px")->display(function(){
                return $this->start_time . '<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;~<br/>' . $this->end_time;
            });
            $grid->column('status')->using((new Events())->model()->status_array())->dot([
                    0 => Admin::color()->yellow(),
                    10 => 'danger',
                    19 => 'error',
                    20 => 'success',
                    30 => 'success',
                    40 => 'primary',
                ], 
                'primary' // 第二个参数为默认值
            );
        
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('user_id');
                $filter->like('title');
                $filter->like('user.nickname', '会员昵称');
                $filter->like('user.phone', '会员手机号');
                $filter->like('service_phone', '举办人手机号');
            });
            $grid->selector(function (Grid\Tools\Selector $selector) {
                $sex_limit_array = (new Events())->model()->sex_limit_array();
                $charge_type_array = (new Events())->model()->charge_type_array();
                $event_type_array = (new Events())->model()->event_type_array();
                $selector->select('sex_limit', '性别限制', array_combine($sex_limit_array, $sex_limit_array));
                $selector->select('charge_type', '收费类型', array_combine($charge_type_array, $charge_type_array));
                $selector->select('event_type', '活动类型', array_combine($event_type_array, $event_type_array));
                $selector->select('status', '状态', (new Events())->model()->status_array());
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
        return Show::make($id, new Events(['user', 'one_level_category', 'two_level_category']), function (Show $show) {
            $show->field('id');
            $show->field('user_id');
            $show->field('user.nickname', '会员昵称');
            $show->field('user.phone', '会员手机号');
            $show->field('event_type');
            $show->field('title');
            $show->field('sex_limit');
            $show->field('charge_type');
            $show->field('award_content');
            $show->field('site_address');
            $show->field('site_longitude');
            $show->field('site_latitude');
            $show->field('start_time');
            $show->field('end_time');
            $show->field('one_level_category.name', '一级分类');
            $show->field('two_level_category.name', '二级分类');
            $show->field('require_content');
            $show->field('image')->image('', 200, 200);
            $show->field('video')->as(function(){
                if($this->video != ''){
                    return '<video width="420" height="240" controls><source src="' . $this->video . '" type="video/mp4"></video>';
                }
            })->unescape();
            $show->field('service_phone');
            $show->field('information_of_registration_key');
            $show->field('status')->using((new Events())->model()->status_array());
            $show->field('reject_cause');
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
        return Form::make(new Events(), function (Form $form) {
            $form->display('id');
            $form->display('title');
            if($form->model()->status == 10 || $form->model()->status == 19){
                $form->radio('status')->options(['19' => '驳回', '20'=> '通过'])->required()->when("=", '19', function(Form $form){
                    $form->text('reject_cause');
                });
            }
            $form->saving(function(Form $form){
                $form->reject_cause = $form->reject_cause ?? '';
                if($form->status == 19){
                    if($form->reject_cause == null){
                        return $form->response()->error("请填写驳回原因");
                    }
                }
            });
            $form->saved(function(Form $form, $result){
                if($form->status == 19){
                    // 发送驳回通知
                    (new \App\Api\Repositories\Log\LogSysMessageRepository())->send_message('活动申请驳回通知', $form->repository()->model()->user_id, '', "您的标题为{$form->repository()->model()->title}的活动被驳回，原因为{$form->repository()->model()->reject_cause}");
                }
                if($form->status == 20){
                    // 将活动加入位置集合
                    (new \App\Api\Repositories\Events\EventsRepository())->add_geo($form->repository()->model()->id, $form->repository()->model()->site_longitude, $form->repository()->model()->site_latitude);
                }
            });
        });
    }
}
