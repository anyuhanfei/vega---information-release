<?php

namespace App\Admin\Metrics\Examples;

use App\Models\Event\Events;
use App\Models\Log\LogUserVip;
use Dcat\Admin\Admin;
use Dcat\Admin\Widgets\Metrics\Donut;

class 活动收费统计 extends Donut
{
    protected $labels = ['免费', '收费', '男收费', '女收费'];

    /**
     * 初始化卡片内容
     */
    protected function init()
    {
        parent::init();

        $color = Admin::color();
        $colors = [$color->primary(), $color->alpha('blue2', 0.8), $color->alpha('yellow', 0.8), $color->alpha('green', 0.8)];

        $this->title('活动收费统计');
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
        $收费_count = Events::where('status', '<>', '-1')->where("charge_type", '收费')->count();
        $免费_count = Events::where('status', '<>', '-1')->where("charge_type", '免费')->count();
        $男收费_count = Events::where('status', '<>', '-1')->where("charge_type", '男收费')->count();
        $女收费_count = Events::where('status', '<>', '-1')->where("charge_type", '女收费')->count();
        $this->withContent($收费_count, $免费_count, $男收费_count, $女收费_count);

        // 图表数据
        $this->withChart([$收费_count, $免费_count, $男收费_count, $女收费_count]);
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
    protected function withContent($收费_count, $免费_count, $男收费_count, $女收费_count)
    {
        $blue = Admin::color()->alpha('blue2', 0.8);
        $yellow = Admin::color()->alpha('yellow', 0.8);
        $green = Admin::color()->alpha('green', 0.8);

        $style = 'margin-bottom: 8px';
        $labelWidth = 120;

        return $this->content(
            <<<HTML
<div class="d-flex pl-1 pr-1 pt-1" style="{$style}">
    <div style="width: {$labelWidth}px">
        <i class="fa fa-circle text-primary"></i> {$this->labels[0]}
    </div>
    <div>{$收费_count}</div>
</div>
<div class="d-flex pl-1 pr-1" style="{$style}">
    <div style="width: {$labelWidth}px">
        <i class="fa fa-circle" style="color: $blue"></i> {$this->labels[1]}
    </div>
    <div>{$免费_count}</div>
</div>
<div class="d-flex pl-1 pr-1" style="{$style}">
    <div style="width: {$labelWidth}px">
        <i class="fa fa-circle" style="color: $yellow"></i> {$this->labels[2]}
    </div>
    <div>{$男收费_count}</div>
</div>
<div class="d-flex pl-1 pr-1" style="{$style}">
    <div style="width: {$labelWidth}px">
        <i class="fa fa-circle" style="color: $green"></i> {$this->labels[3]}
    </div>
    <div>{$女收费_count}</div>
</div>
HTML
        );
    }
}
