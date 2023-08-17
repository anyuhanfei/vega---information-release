<?php
namespace App\Api\Controllers;

use Illuminate\Http\Request;

use App\Api\Controllers\BaseController;

use App\Api\Services\UserLogService;

class UserLogController extends BaseController{

    protected $service;
    public function __construct(Request $request, UserLogService $userLogService){
        parent::__construct($request);
        $this->service = $userLogService;
    }

    /**
     * 会员资产流水记录
     *
     * @param int $page 页码
     * @param int $limit 每页条数
     * @return void
     */
    public function fund_log(Request $request){
        $page = $request->input('page', 1) ?? 1;
        $limit = $request->input('limit', 10) ?? 10;
        $coin_type = $request->input('coin_type', '') ?? '';
        $data = $this->service->get_fund_log_list($this->user_id, $coin_type, $page, $limit);
        return success('资产流水日志', $data);
    }

    /**
     * 测试，资产修改测试
     *
     * @param Request $request
     * @return void
     */
    public function test_fund(Request $request){
        $money = $request->input('money');
        $res = $this->service->test_fund_operation($this->user_id, $money);
        return success('资产修改测试');
    }

    /**
     * 获取系统消息记录列表
     *
     * @param Request $request
     * @return void
     */
    public function sys_message_log(Request $request){
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        $data = $this->service->get_sys_message_list($this->user_id, $page, $limit);
        return success('系统消息', $data);
    }

    /**
     * 获取系统消息的详情
     *  列表中其实已经包含了所有数据，其实可以不要此接口
     *
     * @param Request $request
     * @return void
     */
    public function sys_message_detail(Request $request){
        $id = $request->input('id', 0);
        $data = $this->service->get_sys_message_detail($this->user_id, $id);
        return success('系统消息', $data);
    }

    /**
     * 提现操作
     *
     * @param \App\Api\Requests\User\WithdrawRequest $request
     * @return void
     */
    public function withdraw(\App\Api\Requests\User\WithdrawRequest $request){
        $money = $request->input('money', 0) ?? 0;
        $type = $request->input('type');
        $res = $this->service->user_withdraw_operation($this->user_id, $type, $money);
        return success("提现申请成功");
    }

    /**
     * 提现记录
     *
     * @param \App\Api\Requests\PageRequest $request
     * @return void
     */
    public function withdraw_log(\App\Api\Requests\PageRequest $request){
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        $data = $this->service->user_withdraw_log($this->user_id, $page, $limit);
        return success("提现记录", $data);
    }
}
