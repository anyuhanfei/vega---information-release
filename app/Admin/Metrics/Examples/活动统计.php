<?php

namespace App\Admin\Metrics\Examples;

use App\Models\Event\Events;
use Dcat\Admin\Widgets\Metrics\RadialBar;
use Illuminate\Http\Request;

class 活动统计 extends RadialBar
{
    /**
     * 初始化卡片内容
     */
    protected function init()
    {
        parent::init();

        $this->title('活动统计');
        $this->height(400);
        $this->chartHeight(300);
        $this->chartLabels('活动完成率');
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
                $event_count = Events::where('status', '<>', '-1')->count();
                $status_0_count = Events::where("status", 0)->count();
                $status_10_count = Events::where("status", 10)->count();
                $status_19_count = Events::where("status", 19)->count();
                $status_20_count = Events::where("status", 20)->count();
                $status_30_count = Events::where("status", 30)->count();
                $status_40_count = Events::where("status", 40)->count();
                try {
                    $PCT = ($status_40_count) / ($status_10_count + $status_19_count + $status_20_count + $status_30_count + $status_40_count) * 100;
                } catch (\Exception $e) {
                    $PCT = 100;
                }
                // 卡片内容
                $this->withContent($event_count);
                // 卡片底部
                $this->withFooter($status_0_count, $status_10_count, $status_19_count, $status_20_count, $status_30_count, $status_40_count);
                // 图表数据
                $this->withChart($PCT);
        }
    }

    /**
     * 设置图表数据.
     *
     * @param int $data
     *
     * @return $this
     */
    public function withChart(int $data)
    {
        return $this->chart([
            'series' => [$data],
        ]);
    }

    /**
     * 卡片内容
     *
     * @param string $content
     *
     * @return $this
     */
    public function withContent($content)
    {
        return $this->content(
            <<<HTML
<div class="d-flex flex-column flex-wrap text-center">
    <h1 class="font-lg-2 mt-2 mb-0">{$content}</h1>
    <small>活动总计</small>
</div>
HTML
        );
    }

    /**
     * 卡片底部内容.
     *
     * @param string $new
     * @param string $open
     * @param string $response
     *
     * @return $this
     */
    public function withFooter($s0, $s10, $s19, $s20, $s30, $s40)
    {
        return $this->footer(
            <<<HTML
<div class="d-flex justify-content-between p-1" style="padding-top: 0!important;">
    <div class="text-center">
        <p>未支付</p>
        <span class="font-lg-1">{$s0}</span>
    </div>
    <div class="text-center">
        <p>审核中</p>
        <span class="font-lg-1">{$s10}</span>
    </div>
    <div class="text-center">
        <p>已驳回</p>
        <span class="font-lg-1">{$s19}</span>
    </div>
    <div class="text-center">
        <p>已通过</p>
        <span class="font-lg-1">{$s20}</span>
    </div>
    <div class="text-center">
        <p>进行中</p>
        <span class="font-lg-1">{$s30}</span>
    </div>
    <div class="text-center">
        <p>已完成</p>
        <span class="font-lg-1">{$s40}</span>
    </div>
</div>
HTML
        );
    }
}
