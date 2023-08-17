<?php

namespace App\Admin\Controllers\Event;

use App\Admin\Repositories\Event\EventQa;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

/**
 * 活动问答
 */
class EventQaController extends AdminController{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(){
        return Grid::make(new EventQa(['user', 'event']), function (Grid $grid) {
            $grid->model()->orderBy('id', 'desc');
            // $grid->column('id')->sortable();
            $grid->column('question_id', "类型")->display(function(){
                return $this->question_id == 0 ? "问" : "答";
            });
            $grid->column('content')->tree(true, false)->width('35%')->setAttributes(['style'=> 'word-break:break-all;']);
            $grid->column('user_id', '会员信息')->width('200px')->display(function(){
                try {
                    return '<img data-action="preview-img" src="' . $this->user->avatar . '" style="max-width:60px;cursor:pointer;float:left;" class="img img-thumbnail"><p style="padding-top:3px;">&nbsp;ID: ' . $this->user_id . '</p><p >&nbsp;昵称: ' . $this->user->nickname . '</p>';
                } catch (\Throwable $th) {
                    return "账号已注销";
                }
            });
            $grid->column('event_id', '活动信息')->width("250px")->display(function(){
                try{
                    return '<span style="padding-top:7px;">&nbsp;ID: ' . $this->event_id . '</span><br/><span style="padding-top:7px;">&nbsp;标题: ' . $this->event->title . '</span>';
                }catch(\Exception $e){
                    return "活动已删除";
                }
            });
            $grid->column('created_at');
            // $grid->column('updated_at')->sortable();
            $grid->disableViewButton();
            $grid->disableCreateButton();
            $grid->filter(function (Grid\Filter $filter) {
                $filter->like('content');
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
        return Show::make($id, new EventQa(), function (Show $show) {
            $show->field('id');
            $show->field('user_id');
            $show->field('event_id');
            $show->field('publisher_id');
            $show->field('question_id');
            $show->field('content');
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
        return Form::make(new EventQa(), function (Form $form) {
            $form->display('id');
            $form->text('content');
            $form->disableCreatingCheck();
            $form->disableEditingCheck();
            $form->disableViewButton();
            $form->disableViewCheck();
        });
    }
}
