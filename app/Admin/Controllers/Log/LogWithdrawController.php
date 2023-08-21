<?php

namespace App\Admin\Controllers\Log;

use App\Admin\Repositories\Log\LogWithdraw;
use App\Api\Repositories\User\UserFundsRepository;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class LogWithdrawController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new LogWithdraw(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('user_id', '会员信息')->display(function(){
                try{
                    return '<img data-action="preview-img" src="' . $this->user->avatar . '" style="max-width:60px;cursor:pointer;float:left;" class="img img-thumbnail"><p style="padding-top:3px;">&nbsp;ID: ' . $this->user_id . '</p><p >&nbsp;昵称: ' . $this->user->nickname . '</p>';
                }catch(\Exception $e){
                    return "账号已注销";
                }
            });
            $grid->column('money');
            // $grid->column('fee');
            $grid->column('type');
            $grid->column('account');
            $grid->column('username');
            $grid->column('status')->using((new LogWithdraw())->model()->status_array())->dot([
                    0 => Admin::color()->yellow(),
                    1 => 'danger',
                    2 => 'error',
                ],
                'primary'
            );
            $grid->column('created_at');
            // $grid->column('updated_at')->sortable();
            $grid->disableCreateButton();
            $grid->disableViewButton();
            $grid->disableDeleteButton();
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
        
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
        return Show::make($id, new LogWithdraw(), function (Show $show) {
            $show->field('id');
            $show->field('user_id');
            $show->field('money');
            $show->field('fee');
            $show->field('type');
            $show->field('account');
            $show->field('username');
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
        return Form::make(new LogWithdraw(), function (Form $form) {
            $form->display('id');
            $form->display('user_id');
            $form->display('money');
            $form->display('type');
            $form->display('account');
            $form->display('username');
            $form->radio('status')->options(['1'=> '通过', '2'=> "驳回"])->help("如果选择通过，则系统会自动向用户转账; 如果选择驳回，则提现金额将原路返回会员钱包");
            $form->saving(function(Form $form){
                if($form->model()->status == 1 || $form->model()->status == 2){
                    return $form->response()->error("请勿重复审核");
                }
            });
            $form->saved(function(Form $form, $result){
                if($form->repository()->model()->status == 1){
                    // TODO::转账
                }else{
                    (new UserFundsRepository())->update_fund($form->repository()->model()->user_id, "money", $form->repository()->model()->money, "提现申请驳回");
                }
            });
        });
    }
}
