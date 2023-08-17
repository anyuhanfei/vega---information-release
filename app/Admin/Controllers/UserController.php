<?php

namespace App\Admin\Controllers;

use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Admin\Repositories\User;
use App\Admin\Repositories\User\UserTag;
use App\Api\Repositories\User\UsersRepository;
use App\Api\Repositories\User\UserTagsRepository;
use App\Models\Log\LogUserOperation;
use App\Models\Log\LogUserFund;


class UserController extends BaseController{
    protected int $id;
    protected string $avatar;
    protected string $account;
    protected string $phone;
    protected string $email;
    protected string $nickname;
    protected string $password;
    protected string $level_password;
    protected int $parent_id;
    protected string $sex;
    protected int $is_login;
    protected string $login_type;
    protected string $unionid;
    protected string $openid;
    protected string $third_party;
    protected $funds;
    protected $parent;

    protected function grid(){
        return Grid::make(new User(['detail']), function (Grid $grid) {
            $grid->model()->orderBy('id', 'desc');
            $grid->column('id')->sortable()->width("60px");
            config('admin.users.avatar_show') ? $grid->column('avatar')->image('', 40, 40)->width('50px') : '';
            config('admin.users.nickname_show') ? $grid->column('nickname')->width("150px") : '';
            foreach(config('admin.users.user_identity') as $field){
                $grid->column($field)->width("120px");
            }
            $grid->column('identity', '身份')->width("60px");
            $grid->column('sex', '性别')->width("60px");
            $grid->column('age', '年龄')->width("60px");
            // $grid->column('shop', '商家信息')->width("200px")->display(function(){
            //     if($this->detail->shop_name != ''){
            //         return "名称: {$this->detail->shop_name}<br/>年份: {$this->detail->shop_year}<br/>业务: {$this->detail->shop_business}";
            //     }
            // });
            $grid->column('bio', '个人简介')->width("200px");
            $sys_user = config('admin.users');
            if(count($sys_user['user_funds']) > 0){
                $grid->colum('资金')->display(function() use($sys_user){
                    $str = '';
                    foreach ($sys_user['user_funds'] as $key => $value) {
                        $str .= $value . ': ' . $this->funds->$key . '<br/>';
                    }
                    return $str;
                });
            }
            // if($sys_user['parent_show']){
            //     $grid->column('parent.phone', '上级标识')->display(function() use($sys_user){
            //         if($this->parent_id == 0){
            //             return "";
            //         }
            //         $identity = $sys_user['user_identity'][0];
            //         try{
            //             return $this->parent->$identity;
            //         }catch(\Throwable $th){
            //             return "账号已注销";
            //         }
            //     });
            // }
            $grid->column('is_login')->switch()->help('如果关闭则此会员无法登录');
            $grid->column('created_at');
            $grid->filter(function (Grid\Filter $filter) use($sys_user){
                $filter->equal('id');
                $filter->like('nickname');
                $identity = $sys_user['user_identity'][0];
                $filter->like($identity, '会员标识');
                if($sys_user['parent_show']){
                    $filter->like('parent_id', '上级会员ID');
                    $filter->like('parent.' . $identity, '上级会员标识');
                }
                $filter->equal('is_login')->select(['0'=> '冻结', '1'=> '正常']);
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
        return Show::make($id, new User(['detail']), function (Show $show){
            $show->field('id');
            $show->field('nickname');
            $show->field("avatar")->image("", 80, 80);
            $show->field("phone");
            $show->field("sex", '性别');
            $show->field("identity", '身份');
            $show->field("bio", '个人介绍');
            $show->field("age", '年龄');
            $show->field("vip");
            $show->field("vip_expriation_time", 'VIP到期时间');
            $show->field("background", '背景图片/视频');
            $show->field("shop_name", '商家名称');
            $show->field("shop_year", '商家年份');
            $show->field("shop_business", '商家业务');
            $show->relation('会员标签管理', function ($model) {
                $grid = new Grid(new UserTag());
                $grid->setResource('user/tag');
                $grid->model()->where('user_id', $model->id);
                $grid->id();
                $grid->tag('标签');
                $grid->type('类型');
                $grid->column("like", '点赞数')->display(function(){
                    return (new UserTagsRepository())->get_like_number($this->id);
                });
                $grid->disableRefreshButton();
                $grid->disableFilterButton();
                $grid->disableCreateButton();
                $grid->disableRowSelector();
                $grid->disableEditButton();
                $grid->disableViewButton();
                return $grid;
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(){
        return Form::make(User::with(['funds', 'detail']), function (Form $form) {
            if($form->isCreating()){
                config('admin.users.avatar_show') ? $form->image('avatar')->autoUpload()->uniqueName()->saveFullUrl()->required()->removable(false)->retainable() : '';
                foreach(config('admin.users.user_identity') as $field){
                    $form->text($field)->required();
                }
                config('admin.users.nickname_show') ? $form->text('nickname')->required() : '';
                $form->select("sex", '性别')->options(['男'=> "男", '女'=> '女'])->required();
                $form->select("identity", "身份")->options(['个人'=> "个人", '商家'=> "商家"])->required()->when('=', '商家', function(Form $form){
                    $form->text("detail.shop_name", '商家名称');
                    $form->text("detail.shop_year", '商家年份');
                    $form->text("detail.shop_business", '商家业务');
                });
                $form->text('password')->required();
                if(config('admin.users.level_password_show')){
                    $form->text('level_password')->required();
                }
                if(config('admin.users.parent_show')){
                    $form->select('parent_id', '选择上级')->options((new User())->get_parent_list());
                }
                //将输入的密码加密
                $form->saving(function (Form $form) {
                    $form->password = password_hash($form->password, PASSWORD_DEFAULT);
                    $form->parent_id = $form->parent_id ?? 0;
                    $form->is_login = 1;
                });
                // 同步创建资产表与详情表
                $form->saved(function (Form $form, $result) {
                    $user_funds_repository = new \App\Api\Repositories\User\UserFundsRepository();
                    $user_funds_repository->create_data($result);
                    // $user_detail_repository = new \App\Api\Repositories\User\UserDetailRepository();
                    // $user_detail_repository->create_data($result);
                });
            }else{
                $form->tab('基本信息', function(Form $form){
                    $form->display('id');
                    config('admin.users.avatar_show') ? $form->image('avatar')->autoUpload()->uniqueName()->saveFullUrl()->required()->removable(false)->retainable() : '';
                    foreach(config('admin.users.user_identity') as $field){
                        $form->text($field)->required();
                    }
                    config('admin.users.nickname_show') ? $form->text('nickname')->required() : '';
                    $form->select("sex", '性别')->options(['男'=> "男", '女'=> '女'])->required();
                    $form->select("identity", "身份")->options(['个人'=> "个人", '商家'=> "商家"])->required()->when('=', '商家', function(Form $form){
                        $form->text("detail.shop_name", '商家名称');
                        $form->text("detail.shop_year", '商家年份');
                        $form->text("detail.shop_business", '商家业务');
                    });
                    $form->number("age", '年龄')->required();
                    $form->text("bio", '个人介绍')->required();
                });
                $form->tab('密码', function(Form $form){
                    $form->text('password')->customFormat(function(){
                        return '';
                    })->help('不填写则不修改');
                    if(config('admin.users.level_password_show')){
                        $form->text('level_password')->customFormat(function(){
                            return '';
                        })->help('不填写则不修改');
                    }
                });
                if(count(config('admin.users.user_funds')) > 0){
                    $form->tab('资产', function(Form $form){
                        $user_funds = config('admin.users.user_funds');
                        foreach ($user_funds as $key => $value) {
                            $form->number('funds.' . $key, $value);
                        }
                    });
                }
                //判断是否填写了密码，并加密
                $form->saving(function (Form $form) {
                    $form->avatar = $form->avatar ?? '';
                    $form->nickname = $form->nickname ?? '';
                    if($form->password == null){
                        $form->deleteInput('password');
                    }else{
                        $form->password = password_hash($form->password, PASSWORD_DEFAULT);
                    }
                    if($form->level_password == null){
                        $form->deleteInput('level_password');
                    }
                    // 如果是冻结会员，要删除此会员当前登录的token
                    if($form->is_login === '0'){
                        (new UsersRepository())->delete_token($form->model()->id);
                    }
                });
            }
            $form->hidden('is_login');
            $form->disableViewCheck();
            $form->disableEditingCheck();
            $form->disableCreatingCheck();
        });
    }

    public function get_users(Request $request){
        $nickname = $request->get('q');
        $sys_user = config('admin.users');
        $identity = $sys_user['user_identity'][0];
        return (new User())->model()->get(['id', DB::raw("{$identity} as text")]);
    }
}
