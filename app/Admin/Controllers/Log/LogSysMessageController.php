<?php

namespace App\Admin\Controllers\Log;

use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;

use App\Admin\Controllers\BaseController;

use App\Admin\Repositories\Log\LogSysMessage;
use App\Admin\Repositories\User;


/**
 * 系统消息，mysql 作为存储用于后台展示，redis 保存每个会员的消息id与全员消息
 */
class LogSysMessageController extends BaseController{
    protected int $id;
    protected string $user_ids;
    // protected string $title;
    protected string $image;
    protected string $content;

    protected function grid(){
        return Grid::make(new LogSysMessage(), function (Grid $grid) {
            $grid->model()->orderBy('id', 'desc');
            $grid->column('id')->sortable();
            // $grid->column('uids');
            $grid->column('title', config('admin.sys_message.content_show') ? '标题' : '内容')->width('30%');
            $sys_user = config('admin.users');
            $grid->column('user_identity')->width('30%')->display(function() use($sys_user){
                if($this->user_ids == 0){
                    return "所有会员";
                }
                return implode(', ', (new User())->use_ids_get_identities_arr($this->user_ids));
            })->limit(30, '...');
            config('admin.sys_message.image_show') ? $grid->column('image')->image('', 40, 40) : '';
            config('admin.sys_message.content_show') ? '' : $grid->disableViewButton();
            $grid->column('created_at');

            $grid->disableEditButton();

            $grid->filter(function (Grid\Filter $filter) use($sys_user) {
                $filter->equal('id');
                $filter->where("user_id", function($query){
                    $query->whereRaw("FIND_IN_SET('{$this->input}', user_ids) > 0");
                });
                $identity = $sys_user['user_identity'][0];
                $filter->where("user_identity", function($query) use($identity){
                    $user = (new User())->model()->where($identity, $this->input)->first();
                    if($user){
                        $query->whereRaw("FIND_IN_SET('{$user->id}', user_ids) > 0");
                    }else{
                        $query->where("user_ids", "-1");
                    }
                });
                $filter->like('title');
                $filter->between('created_at')->datetime();
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
    protected function detail($id){
        return Show::make($id, new LogSysMessage(), function (Show $show) {
            $show->field('id');
            $show->field('user_ids');
            $show->field('title');
            $show->field('image');
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
        return Form::make(new LogSysMessage(), function (Form $form) {
            $form->display('id');
            $form->multipleSelect('user_ids')->options("get/users")->help('不选择表示所有会员')->saving(function ($value) {
                return $value ? implode(',', $value) : '0';
            });
            if(config('admin.sys_message.image_show')){
                $form->image('image')->autoUpload()->uniqueName()->saveFullUrl()->required();
            }
            if(config('admin.sys_message.content_show')){
                $form->text('title')->required();
                $form->editor('content')->height('600')->disk(config('admin.upload_disk'))->required();
            }else{
                $form->text('title', '内容')->required();
                $form->hidden('content');
            }
            $form->saving(function (Form $form) {
                $form->content = $form->content ?? '';
            });

            // 将数据同步保存到redis中，为了保证数据一致和代码的简洁，删除了修改功能
            $form->saved(function(Form $form, $result){
                $form->user_ids = array_filter($form->user_ids) ? implode(',', array_filter($form->user_ids)) : '0';
                if($form->isCreating()){
                    (new LogSysMessage())->save_uid_to_redis($form->getKey(), $form->user_ids);
                    (new LogSysMessage())->save_data_to_redis($form->getKey(), $form->title ?? '', $form->image ?? '', $form->content ?? '');
                }
            });
            // 同步将redis的数据删除
            $form->deleted(function(Form $form, $result){
                $data = $form->model()->toArray()[0];
                (new LogSysMessage())->delete_data_form_redis($data['id'], $data['uids']);
            });

            $form->tools(function (Form\Tools $tools) {
                $tools->disableView();
            });
            $form->disableViewCheck();
            $form->disableEditingCheck();
            $form->disableCreatingCheck();
            $form->disableDeleteButton();
        });
    }
}
