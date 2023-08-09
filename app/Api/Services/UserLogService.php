<?php
namespace App\Api\Services;

use App\Api\Repositories\Log\LogUserFundRepository;
use App\Api\Repositories\Log\LogSysMessageRepository;
use App\Api\Repositories\User\UserFundsRepository;

class UserLogService{
    /**
     * 获取资产流水记录
     *
     * @param int $user_id 会员id
     * @param int $page 页码
     * @param integer $limit 每页条数
     * @return void
     */
    public function get_fund_log_list(int $user_id, string $coin_type, int $page, int $limit = 10){
        return (new LogUserFundRepository())->get_list($user_id, $coin_type, $page, $limit);
    }

    /**
     * 测试，修改资产
     *
     * @param integer $user_id
     * @param integer|float $money
     * @return void
     */
    public function test_fund_operation(int $user_id, int|float $money){
        (new UserFundsRepository())->update_fund($user_id, 'money', $money, '测试');
        return true;
    }

    /**
     * 获取系统消息列表
     *
     * @param integer $user_id 会员id
     * @param integer $page 页码
     * @param integer $limit 每页条数
     * @return void
     */
    public function get_sys_message_list(int $user_id, int $page, int $limit = 10):array{
        $LogSysMessageRepository = new LogSysMessageRepository();
        $data = $LogSysMessageRepository->use_uid_get_datas_form_redis($user_id, $page, $limit);
        $list_read = config('admin.sys_message.list_read');
        foreach($data as &$value){
            $value['is_read'] = $LogSysMessageRepository->get_read_status($user_id, $value['id']);
            if($list_read){
                $LogSysMessageRepository->set_read_status($user_id, $value['id']);
            }
        }
        return $data;
    }

    /**
     * 获取系统消息详情
     *
     * @param integer $user_id 会员id
     * @param integer $id 系统消息id
     * @return array
     */
    public function get_sys_message_detail(int $user_id, int $id):array{
        $LogSysMessageRepository = new LogSysMessageRepository();
        $data = $LogSysMessageRepository->use_id_get_data_form_redis($id);
        $LogSysMessageRepository->set_read_status($user_id, $data['id']);  # 设置为已读（无论设置如何，这里必须设置已读）
        return $data;
    }
}