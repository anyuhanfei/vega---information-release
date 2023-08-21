<?php

namespace App\Admin\Metrics\Examples;

use App\Models\Log\LogUserVip;
use Dcat\Admin\Admin;
use Dcat\Admin\Widgets\Metrics\Donut;

class VIP统计 extends Donut
{
    protected $labels = ['包月', '包季', '包年'];

    /**
     * 初始化卡片内容
     */
    protected function init()
    {
        parent::init();

        $color = Admin::color();
        $colors = [$color->primary(), $color->alpha('blue2', 0.7), $color->alpha('blue2', 0.4)];

        $this->title('VIP');
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
        $包月_count = LogUserVip::where("vip_name", "包月")->count();
        $包季_count = LogUserVip::where("vip_name", "包季")->count();
        $包年_count = LogUserVip::where("vip_name", "包年")->count();
        $this->withContent($包月_count, $包季_count, $包年_count);

        // 图表数据
        $this->withChart([$包月_count, $包季_count, $包年_count]);
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
    protected function withContent($包月_count, $包季_count, $包年_count)
    {
        $blue = Admin::color()->alpha('blue2', 0.7);
        $blue2 = Admin::color()->alpha('blue2', 0.4);

        $style = 'margin-bottom: 8px';
        $labelWidth = 120;

        return $this->content(
            <<<HTML
<div class="d-flex pl-1 pr-1 pt-1" style="{$style}">
    <div style="width: {$labelWidth}px">
        <i class="fa fa-circle text-primary"></i> {$this->labels[0]}
    </div>
    <div>{$包月_count}</div>
</div>
<div class="d-flex pl-1 pr-1" style="{$style}">
    <div style="width: {$labelWidth}px">
        <i class="fa fa-circle" style="color: $blue"></i> {$this->labels[1]}
    </div>
    <div>{$包季_count}</div>
</div>
<div class="d-flex pl-1 pr-1" style="{$style}">
    <div style="width: {$labelWidth}px">
        <i class="fa fa-circle" style="color: $blue2"></i> {$this->labels[2]}
    </div>
    <div>{$包年_count}</div>
</div>
HTML
        );
    }
}
