<?php

namespace App\Admin\Controllers\Log;

use App\Admin\Repositories\Log\LogUserFund;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use App\Admin\Controllers\BaseController;
use Exception;

class LogUserFundController extends BaseController{
    protected int $id;
    protected int $user_id;
    protected string $number;
    protected string $coin_type;
    protected string $fund_type;
    protected string $content;
    protected string $remark;
    protected $user;

    protected function grid(){
        return Grid::make(new LogUserFund(['user']), function (Grid $grid) {
            $grid->model()->orderBy('id', 'desc');
            $grid->column('id')->sortable();
            $grid->column('user_id', '会员ID');
            $sys_user = config('admin.users');
            $grid->column('user_identity')->display(function() use($sys_user){
                $identity = $sys_user['user_identity'][0];
                try{
                    return $this->user->$identity;
                }catch(\Throwable $th){
                    return "此会员已注销";
                }
            });
            $grid->column('number');
            $grid->column('coin_type');
            $grid->column('fund_type')->width("15%");
            $grid->column('content')->width("15%");
            // $grid->column('remark')->width('10%');
            $grid->column('created_at');

            $grid->disableViewButton();
            $grid->disableEditButton();
            $grid->disableDeleteButton();
            $grid->disableQuickEditButton();
            $grid->disableCreateButton();
            $grid->filter(function (Grid\Filter $filter) use($sys_user){
                $filter->equal('id');
                $filter->equal('uid');
                $identity = $sys_user['user_identity'][0];
                $filter->like('user.' . $identity, '会员标识');
                $filter->equal('coin_type')->select($sys_user['user_funds']);
                $filter->equal('fund_type')->select($sys_user['fund_type']);
                $filter->between('number');
                $filter->between('created_at')->datetime();
            });
        });
    }
}
