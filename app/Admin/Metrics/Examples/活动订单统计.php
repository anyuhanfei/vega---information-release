<?php

namespace App\Admin\Metrics\Examples;

use App\Models\Event\EventOrders;
use Dcat\Admin\Widgets\Metrics\Round;
use Illuminate\Http\Request;

class 活动订单统计 extends Round
{
    /**
     * 初始化卡片内容
     */
    protected function init()
    {
        parent::init();

        $this->title('活动订单统计');
        $this->chartLabels(['审核中', '已拒绝', '已同意', '进行中', '已完成']);
        $this->dropdown([
            'all' => '全部',
        ]);
    }

    /**
     * 处理请求
     *
     * @param Request $request
     *
     * @return mixed|void
     */
    public function handle(Request $request)
    {
        switch ($request->get('option')) {
            case '365':
            case '30':
            case '28':
            case '7':
            default:
                $status_10_count = EventOrders::where("status", 10)->count();
                $status_19_count = EventOrders::where("status", 19)->count();
                $status_20_count = EventOrders::where("status", 20)->count();
                $status_30_count = EventOrders::where("status", 30)->count();
                $status_40_count = EventOrders::whereIn("status", [40, 50])->count();
                // 卡片内容
                $this->withContent($status_10_count, $status_19_count, $status_20_count, $status_30_count, $status_40_count);

                // 图表数据
                $this->withChart([$status_10_count, $status_19_count, $status_20_count, $status_30_count, $status_40_count]);

                // 总数
                $this->chartTotal('总数', $status_10_count + $status_19_count + $status_20_count + $status_30_count + $status_40_count);
        }
    }

    /**
     * 设置图表数据.
     *
     * @param array $data
     *
     * @return $this
     */
    public function withChart(array $data)
    {
        return $this->chart([
            'series' => $data,
        ]);
    }

    /**
     * 卡片内容.
     *
     * @param int $finished
     * @param int $pending
     * @param int $rejected
     *
     * @return $this
     */
    public function withContent($s10, $s19, $s20, $s30, $s40)
    {
        return $this->content(
            <<<HTML
<div class="col-12 d-flex flex-column flex-wrap text-center" style="max-width: 220px">
    <div class="chart-info d-flex justify-content-between mb-1 mt-2" >
          <div class="series-info d-flex align-items-center">
              <i class="fa fa-circle-o text-bold-700 text-primary"></i>
              <span class="text-bold-600 ml-50">审核中</span>
          </div>
          <div class="product-result">
              <span>{$s10}</span>
          </div>
    </div>

    <div class="chart-info d-flex justify-content-between mb-1">
          <div class="series-info d-flex align-items-center">
              <i class="fa fa-circle-o text-bold-700 text-warning"></i>
              <span class="text-bold-600 ml-50">已拒绝</span>
          </div>
          <div class="product-result">
              <span>{$s19}</span>
          </div>
    </div>

     <div class="chart-info d-flex justify-content-between mb-1">
          <div class="series-info d-flex align-items-center">
              <i class="fa fa-circle-o text-bold-700 text-danger"></i>
              <span class="text-bold-600 ml-50">已通过</span>
          </div>
          <div class="product-result">
              <span>{$s20}</span>
          </div>
    </div>

    <div class="chart-info d-flex justify-content-between mb-1">
          <div class="series-info d-flex align-items-center">
              <i class="fa fa-circle-o text-bold-700 text-success"></i>
              <span class="text-bold-600 ml-50">进行中</span>
          </div>
          <div class="product-result">
              <span>{$s30}</span>
          </div>
    </div>

    <div class="chart-info d-flex justify-content-between mb-1">
          <div class="series-info d-flex align-items-center">
              <i class="fa fa-circle-o text-bold-700 text-success"></i>
              <span class="text-bold-600 ml-50">已完成</span>
          </div>
          <div class="product-result">
              <span>{$s40}</span>
          </div>
    </div>
</div>
HTML
        );
    }
}
