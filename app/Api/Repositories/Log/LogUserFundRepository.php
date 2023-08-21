<?php
namespace App\Api\Repositories\Log;

use App\Models\Log\LogUserFund as Model;
use Illuminate\Support\Facades\Cache;

class LogUserFundRepository{
    protected $eloquentClass = Model::class;
    protected $cache_prefix = 'user_fund_log';

    /**
     * 获取资产流水记录
     *
     * @param integer $user_id
     * @param string $coin_type
     * @param integer $page
     * @param integer $limit
     * @return void
     */
    public function get_list(int $user_id, string $coin_type, int $page, int $limit){
        return $this->eloquentClass::uid($user_id)->coinType($coin_type)->page($page, $limit)->get();
    }

    /**
     * 清除缓存
     *
     * @param int $user_id 会员id
     * @return void
     */
    public function delete_cache($user_id){
        Cache::tags(["{$this->cache_prefix}:{$user_id}"])->flush();
        return true;
    }

    /**
     * 创建资金流水记录
     *
     * @param integer $user_id
     * @param string $coin_type
     * @param integer|float $money
     * @param string $fund_type
     * @param string $content
     * @param string $remark
     * @return void
     */
    public function created_data(int $user_id, string $coin_type, int|float $money, string $fund_type, string $content = '', string $remark = ''){
        return $this->eloquentClass::create([
            'user_id' => $user_id,
            'coin_type' => $coin_type,
            'number' => $money,
            'fund_type' => $fund_type,
            'content' => $content,
            'remark' => $remark,
        ]);
    }

    public function use_fund_type_get_list(int $user_id, string $fund_type, int $page, int $limit){
        return $this->eloquentClass::userId($user_id)->fundType('%'.$fund_type.'%')->page($page, $limit)->get();
    }

    /**
     * 删除指定会员的日志
     *
     * @param integer $user_id
     * @return void
     */
    public function delete_user_data(int $user_id){
        return $this->eloquentClass::userId($user_id)->delete();
    }
}