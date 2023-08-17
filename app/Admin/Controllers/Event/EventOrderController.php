<?php

namespace App\Admin\Controllers\Event;

use App\Admin\Repositories\Event\EventOrder;
use Dcat\Admin\Form;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

/**
 * 活动订单列表
 */
class EventOrderController extends AdminController{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(){
        return Grid::make(new EventOrder(['publisher', 'user', 'event']), function (Grid $grid) {
            // $grid->column('id')->sortable();
            $grid->model()->orderBy('id', 'desc');
            $grid->column('order_no');
            $grid->column('user_id', '会员信息')->width('200px')->display(function(){
                try{
                    return '<img data-action="preview-img" src="' . $this->user->avatar . '" style="max-width:60px;cursor:pointer;float:left;" class="img img-thumbnail"><p style="padding-top:3px;">&nbsp;ID: ' . $this->user_id . '</p><p >&nbsp;昵称: ' . $this->user->nickname . '</p>';
                }catch(\Exception $e){
                    return "账号已注销";
                }
                
            });
            $grid->column('publisher_id', '举办人信息')->width('200px')->display(function(){
                try{
                    return '<img data-action="preview-img" src="' . $this->publisher->avatar . '" style="max-width:60px;cursor:pointer;float:left;" class="img img-thumbnail"><p style="padding-top:3px;">&nbsp;ID: ' . $this->publisher_id . '</p><p >&nbsp;昵称: ' . $this->publisher->nickname . '</p>';
                }catch(\Exception $e){
                    return "账号已注销";
                }
            });
            $grid->column('event_id', '活动信息')->width("350px")->display(function(){
                try{
                    return '<img data-action="preview-img" src="' . $this->event->image . '" style="max-width:140px;max-height:140px;cursor:pointer;float:left;" class="img img-thumbnail"><h6 style="padding-top:7px;">&nbsp;' . $this->event->title . '(ID: ' . $this->event_id . ')</h6>&nbsp;&nbsp;单&nbsp;&nbsp;&nbsp;价&nbsp;&nbsp;：¥<b>' . $this->unit_price . '</b>&nbsp;x' . $this->number . '<br/>&nbsp;&nbsp;总&nbsp;&nbsp;&nbsp;价&nbsp;&nbsp;：¥<b>' . $this->all_price . '</b><br/>支付金额：¥<b>' . $this->pay_price . '</b>';
                }catch(\Exception $e){
                    return "活动已删除";
                }
            });
            $grid->column('information_of_registration_value')->width("200px")->display(function(){
                $value = json_decode($this->information_of_registration_value);
                $str = "";
                foreach($value as $k=> $v){
                    $str .= $k . '：' . $v . '<br/>';
                }
                return $str;
            });
            $grid->column('status')->using((new EventOrder())->model()->status_array())->dot([
                    0 => Admin::color()->yellow(),
                    10 => 'danger',
                    19 => 'error',
                    20 => 'success',
                    30 => 'success',
                    40 => 'primary',
                    50 => 'primary',
                ], 
                'primary'
            );
            $grid->column('created_at')->width("100px");
            $grid->selector(function (Grid\Tools\Selector $selector) {
                $selector->select('status', '状态', (new EventOrder())->model()->status_array());
            });
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
                $filter->equal("user_id");
                $filter->like("user.nickname", "报名人昵称");
                $filter->like("user.phone", '会员手机号');
                $filter->equal("publisher_id");
                $filter->like("publisher.nickname", "举办人昵称");
                $filter->like("publisher.phone", '举办人手机号');
                $filter->equal("event_id", '活动id');
                $filter->like("event.title", '活动标题');
            });
            $grid->disableCreateButton();
            $grid->disableDeleteButton();
            $grid->disableEditButton();
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
        return Show::make($id, new EventOrder(), function (Show $show) {
            $show->field('id');
            $show->field('order_no');
            $show->field('user_id');
            $show->field('event_id');
            $show->field('publisher_id');
            $show->field('number');
            $show->field('unit_price');
            $show->field('all_price');
            $show->field('pay_price');
            $show->field('information_of_registration_value');
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
        return Form::make(new EventOrder(), function (Form $form) {
            $form->display('id');
            $form->text('order_no');
            $form->text('user_id');
            $form->text('event_id');
            $form->text('publisher_id');
            $form->text('number');
            $form->text('unit_price');
            $form->text('all_price');
            $form->text('pay_price');
            $form->text('information_of_registration_value');
            $form->text('status');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
