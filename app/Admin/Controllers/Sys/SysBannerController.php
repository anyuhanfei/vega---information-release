<?php

namespace App\Admin\Controllers\Sys;

use Dcat\Admin\Form;
use Dcat\Admin\Grid;

use App\Admin\Repositories\Sys\SysBanner;
use App\Admin\Controllers\BaseController;
use App\Models\Sys\SysBanner as SysBannerModel;
use App\Api\Repositories\Sys\SysBannerRepository;

class SysBannerController extends BaseController{
    protected int $id;
    protected string $image;
    protected string $url;
    protected array $site_array;

    public function __construct(){
        $this->site_array = SysBannerModel::site_array();
    }


    protected function grid(){
        if(config('admin.banner.banner_show') == false){
            return admin_error('error', '当前已关闭轮播图功能，请删除此目录或联系管理员打开轮播图功能');
        }
        return Grid::make(new SysBanner(), function (Grid $grid) {
            $grid->column('id')->sortable();
            if(count($this->site_array) != 1){
                $grid->column("site", '位置');
                $grid->selector(function (Grid\Tools\Selector $selector){
                    $selector->select('site', '位置', array_combine($this->site_array, $this->site_array));
                });
            }
            $grid->column('image')->image('', 60, 60);
            if(config('admin.banner.url_show')){
                $grid->column('url');
            }

            $grid->disableFilterButton();
            $grid->disableViewButton();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
                $filter->equal("site")->select(array_combine($this->site_array, $this->site_array));
            });
        });
    }

    protected function form(){
        return Form::make(new SysBanner(), function (Form $form) {
            $form->tools(function (Form\Tools $tools) {
                $tools->disableView();
                $tools->disableDelete();
            });
            $form->display('id');
            if(count($this->site_array) == 1){
                $form->hidden("site")->value($this->site_array[0]);
            }else{
                $form->select("site", '位置')->options(array_combine($this->site_array, $this->site_array))->required();
            }
            $form->image('image')->autoUpload()->uniqueName()->saveFullUrl()->required()->removable(false)->retainable();
            if(config('admin.banner.url_show')){
                $form->text('url')->required();
            }

            // 清除缓存
            $form->saving(function(Form $form){
                (new SysBannerRepository())->del_cache($form->site);
                if($form->isEditing()){
                    (new SysBannerRepository())->del_cache($form->model()->site);
                }
            });
            $form->deleted(function(Form $form, $result){
                (new SysBannerRepository())->del_cache($form->site);
            });

            $form->disableViewCheck();
            $form->disableEditingCheck();
            $form->disableCreatingCheck();
        });
    }
}
