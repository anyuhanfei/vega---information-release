<?php

namespace App\Admin\Controllers\Log;

use App\Admin\Repositories\Log\LogFeedback;
use App\Api\Repositories\Log\LogSysMessageRepository;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class LogFeedbackController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new LogFeedback(['user']), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->model()->orderBy('id', 'desc');
            $grid->column('user_id', '会员信息')->width('200px')->display(function(){
                try{
                    return '<img data-action="preview-img" src="' . $this->user->avatar . '" style="max-width:60px;cursor:pointer;float:left;" class="img img-thumbnail"><p style="padding-top:3px;">&nbsp;ID: ' . $this->user_id . '</p><p >&nbsp;昵称: ' . $this->user->nickname . '</p>';
                }catch(\Exception $e){
                    return "账号已注销";
                }
            });
            $grid->column('title', '举报内容')->width("400px")->display(function(){
                return "<b>" . $this->title . "</b><br/>" . $this->content;
            });
            $grid->column('images')->display(function(){
                return $this->images == '' ? '' : comma_str_to_array($this->images);
            })->image("", 40, 40);
            $grid->column('admin_remark')->editable()->help("这里仅管理员后台自己查看");
            $grid->column('is_reply')->using((new LogFeedback())->model()->is_reply_array())->dot([
                    0 => Admin::color()->yellow(),
                    1 => 'success',
                ],
                'primary'
            );
            $grid->column('created_at');
            // $grid->column('updated_at')->sortable();
            $grid->disableCreateButton();
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
                // $filter->like('order_no', '订单编号');
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
        return Show::make($id, new LogFeedback(), function (Show $show) {
            $show->field('id');
            $show->field('user_id');
            $show->field('title');
            $show->field('content');
            $show->field('images')->as(function(){
                return explode(',', $this->images);
            })->image("", 100, 100);
            $show->field('video')->as(function(){
                if($this->video != ''){
                    return '<video width="420" height="240" controls><source src="' . $this->video . '" type="video/mp4"></video>';
                }
            })->unescape();
            $show->field('admin_remark');
            $show->field('is_reply')->using((new LogFeedback())->model()->is_reply_array())->dot([
                    0 => Admin::color()->yellow(),
                    1 => 'success',
                ], 
                'primary'
            );
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
        return Form::make(new LogFeedback(), function (Form $form) {
            $form->display('id');
            $form->display('title');
            $form->display('content');
            $form->hidden('is_reply');
            $form->text("reply", "回复")->required()->help("回复内容将通过系统消息发送至会员通知中");
            $form->saving(function(Form $form){
                (new LogSysMessageRepository())->send_message("意见反馈回复", $form->model()->user_id, '', $form->reply);
                $form->deleteInput('reply');
                $form->is_reply = 1;
            });
        });
    }
}
