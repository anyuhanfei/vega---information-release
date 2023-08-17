<?php

namespace App\Admin\Controllers\Event;

use App\Admin\Repositories\Event\EventOrderEvaluate;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class EventOrderEvaluateController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new EventOrderEvaluate(['user', 'event']), function (Grid $grid) {
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
            $grid->column('event_id', '活动信息')->width("200px")->display(function(){
                try{
                    return '<span style="padding-top:7px;">&nbsp;ID: ' . $this->event_id . '</span><br/><span style="padding-top:7px;">&nbsp;标题: ' . $this->event->title . '</span>';
                }catch(\Exception $e){
                    return "活动已删除";
                }
            });
            // $grid->column('publisher_id');
            $grid->column('score')->display(function(){
                return (new EventOrderEvaluate())->score_text($this->score);
            });
            $grid->column('tags', '选用标签')->display(function(){
                $str = '';
                foreach(comma_str_to_array($this->tags) as $tag){
                    $str .= '<span class="label" style="background:#586cb1">' . $tag . '</span>';
                }
                return $str;
            });
            // $grid->column('is_anonymity');
            $grid->column('created_at');
            // $grid->column('updated_at')->sortable();
            $grid->disableCreateButton();
            $grid->disableViewButton();
            $grid->disableEditButton();
            $grid->selector(function (Grid\Tools\Selector $selector) {
                $selector->select('score', '评分', (new EventOrderEvaluate())->model()->score_array());
            });
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
                $filter->like('order_no');
                $filter->equal("user_id");
                $filter->like("user.nickname", "报名人昵称");
                $filter->like("user.phone", '会员手机号');
                $filter->equal("publisher_id");
                $filter->like("publisher.nickname", "举办人昵称");
                $filter->like("publisher.phone", '举办人手机号');
                $filter->equal("event_id", '活动id');
                $filter->like("event.title", '活动标题');
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
        return Show::make($id, new EventOrderEvaluate(), function (Show $show) {
            $show->field('id');
            $show->field('order_no');
            $show->field('user_id');
            $show->field('event_id');
            $show->field('publisher_id');
            $show->field('score');
            $show->field('tags');
            $show->field('is_anonymity');
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
        return Form::make(new EventOrderEvaluate(), function (Form $form) {
            $form->display('id');
            $form->text('order_no');
            $form->text('user_id');
            $form->text('event_id');
            $form->text('publisher_id');
            $form->text('score');
            $form->text('tags');
            $form->text('is_anonymity');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
