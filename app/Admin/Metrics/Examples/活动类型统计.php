<?php

namespace App\Admin\Metrics\Examples;

use App\Models\Event\Events;
use App\Models\Log\LogUserVip;
use Dcat\Admin\Admin;
use Dcat\Admin\Widgets\Metrics\Donut;

class 活动类型统计 extends Donut
{
    protected $labels = ['公益活动', '商业活动'];

    /**
     * 初始化卡片内容
     */
    protected function init()
    {
        parent::init();

        $color = Admin::color();
        $colors = [$color->primary(), $color->alpha('blue2', 0.7)];

        $this->title('活动类型统计');
        $this->subTitle('全部');
        $this->chartLabels($this->labels);
        // 设置图表颜色
        $this->chartColors($colors);
    }

    /**
     * 渲染模板
     *
     * @return string
     */
    public function render()
    {
        $this->fill();

        return parent::render();
    }

    /**
     * 写入数据.
     *
     * @return void
     */
    public function fill()
    {
        $公益活动_count = Events::where('status', '<>', '-1')->where("event_type", '公益活动')->count();
        $商业活动_count = Events::where('status', '<>', '-1')->where("event_type", '商业活动')->count();
        $this->withContent($公益活动_count, $商业活动_count);

        // 图表数据
        $this->withChart([$公益活动_count, $商业活动_count]);
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
            'series' => $data
        ]);
    }

    /**
     * 设置卡片头部内容.
     *
     * @param mixed $desktop
     * @param mixed $mobile
     *
     * @return $this
     */
    protected function withContent($公益活动_count, $商业活动_count)
    {
        $blue = Admin::color()->alpha('blue2', 0.7);

        $style = 'margin-bottom: 8px';
        $labelWidth = 120;

        return $this->content(
            <<<HTML
<div class="d-flex pl-1 pr-1 pt-1" style="{$style}">
    <div style="width: {$labelWidth}px">
        <i class="fa fa-circle text-primary"></i> {$this->labels[0]}
    </div>
    <div>{$公益活动_count}</div>
</div>
<div class="d-flex pl-1 pr-1" style="{$style}">
    <div style="width: {$labelWidth}px">
        <i class="fa fa-circle" style="color: $blue"></i> {$this->labels[1]}
    </div>
    <div>{$商业活动_count}</div>
</div>
HTML
        );
    }
}
