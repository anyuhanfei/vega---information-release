<?php
namespace App\Api\Repositories\User;

use App\Models\User\UserFunds as Model;
use App\Api\Repositories\Log\LogUserFundRepository;
use App\Api\Repositories\Sys\SysSettingRepository;

class UserFundsRepository{
    protected $eloquentClass = Model::class;

    /**
     * 创建会员账号时，初始化会员资产
     *
     * @param integer $user_id
     * @return void
     */
    public function create_data(int $user_id){
        // 需要获取到设置的信用分
        $初始信用分 = (new SysSettingRepository())->use_id_get_value(32);
        return $this->eloquentClass::create([
            'id'=> $user_id,
            'money'=> 0,
            'credit'=> intval($初始信用分),
        ]);
    }

    /**
     * 初始化会员资产信息
     *
     * @param integer $user_id
     * @return void
     */
    public function init_data(int $user_id){
        $初始信用分 = (new SysSettingRepository())->use_id_get_value(32);
        return $this->eloquentClass::where("id", $user_id)->update([
            'money'=> 0,
            'credit'=> intval($初始信用分),
        ]);
    }

    /**
     * 对会员的资金进行操作并添加记录信息
     * 正常情况下，需要在事务内调用此方法，可以让悲观锁生效
     *
     * @param int $user_id 会员id
     * @param string $coin_type 币种
     * @param float|int $money 金额
     * @param string $fund_type 操作类型
     * @param string $content 操作说明
     * @param string $remark 备注
     * @return bool
     */
    public function update_fund(int $user_id, string $coin_type, float|int $money, string $fund_type, string $content = '', string $remark = ''){
        // 查询数据并获取排它锁，修改会员资产
        $user_fund = $this->eloquentClass::where('id', $user_id)->lockForUpdate()->first();
        $user_fund->$coin_type += $money;
        $res_one = $user_fund->save();
        // 添加资产记录
        $LogUserFundRepository = new LogUserFundRepository();
        $res_two = $LogUserFundRepository->created_data($user_id, $coin_type, $money, $fund_type, $content, $remark);
        return $res_one && $res_two;
    }

    /**
     * 获取会员的某项资产
     *
     * @param integer $user_id
     * @param string $field
     * @return void
     */
    public function get_user_fund(int $user_id, string $field){
        return $this->eloquentClass::where("id", $user_id)->value($field) ?? 0;
    }
}