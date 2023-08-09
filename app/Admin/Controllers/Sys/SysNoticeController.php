<?php

namespace App\Admin\Controllers\Sys;

use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Widgets\Card;
use App\Admin\Controllers\BaseController;

use App\Models\Sys\SysNotice as SysNoticeModel;
use App\Admin\Repositories\Sys\SysNotice;
use App\Api\Repositories\Sys\SysNoticeRepository;

class SysNoticeController extends BaseController{
    protected int $id;
    // protected string $title;
    protected string $content;
    protected string $image;

    protected function grid(){
        if(config('admin.notice.notice_show') == false){
            return admin_error('error', '当前已关闭公告功能，请删除此目录或联系管理员打开公告功能');
        }
        return Grid::make(new SysNotice(), function (Grid $grid) {
            $grid->model()->orderBy('id', 'desc');
            switch(config('admin.notice.type')) {
                case '单条富文本':
                    SysNoticeModel::init();
                    $grid->column('');
                    $grid->column('title');
                    $grid->column('content')->width('15%')->display('')->modal(function ($modal) {
                        $modal->title($this->title);
                        $this->content == null ? $modal->icon('feather ') : $modal->icon('feather icon-eye');
                        $card = new Card(null, $this->content);
                        return "<div style='padding:10px 10px 0'>$card</div>";
                    });
                    $grid->disableDeleteButton();
                    $grid->disableViewButton();
                    $grid->disableCreateButton();
                    $grid->disableFilter();
                    break;
                case "多条文字":
                    $grid->column('id')->sortable();
                    $grid->column('title')->width("50%");
                    break;
                case '多条富文本':
                    $grid->column('id')->sortable();
                    $grid->column('title')->width("40%");
                    break;
                default:
                    SysNoticeModel::init();
                    $grid->column('');
                    $grid->column('title')->width("60%");
                    $grid->disableViewButton();
                    $grid->disableDeleteButton();
                    $grid->disableCreateButton();
                    $grid->disableFilter();
                    break;
            }
            if(config('admin.notice.image_show')){
                $grid->column('image')->image('', 40, 40);
            }
            $grid->column('created_at');
            $grid->disableRowSelector();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
                $filter->like('title');
            });
        });
    }

    protected function detail($id){
        return Show::make($id, new SysNotice(), function (Show $show) {
            $show->field('id');
            $show->field('title');
            $show->field('content')->unescape();
            $show->field('image')->image();
            $show->field('created_at');
            $show->field('updated_at');
            $show->panel()->tools(function ($tools) {
                $tools->disableDelete();
            });
        });
    }

    protected function form(){
        return Form::make(new SysNotice(), function (Form $form) {
            $form->hidden('id');
            config('admin.notice.image_show') ? $form->image('image')->autoUpload()->uniqueName()->saveFullUrl()->required()->removable(false)->retainable() : '';
            $form->text('title')->required();
            switch(config('admin.notice.type')) {
                case '单条富文本':
                    $form->editor('content')->height('600')->disk(config('admin.upload_disk'))->required();
                    break;
                case '多条富文本':
                    $form->editor('content')->height('600')->disk(config('admin.upload_disk'))->required();
                    break;
                default:
                    $form->hidden('content');
                    break;
            }
            $form->saving(function (Form $form) {
                $form->content = $form->content ?? '';
            });

            // 清除缓存
            $form->saved(function(Form $form, $result){
                (new SysNoticeRepository())->del_cache($form->id ?? $form->model()->id ?? $result);
            });
            $form->deleted(function(Form $form, $result){
                $data_id = $form->model()->toArray()[0]['id'];
                (new SysNoticeRepository())->del_cache($data_id);
            });

            $form->disableViewCheck();
            $form->disableEditingCheck();
            $form->disableCreatingCheck();
            $form->disableDeleteButton();
        });
    }
}
