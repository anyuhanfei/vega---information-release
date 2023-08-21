<?php

namespace App\Admin\Metrics\Examples;

use App\Models\User\Users;
use Dcat\Admin\Admin;
use Dcat\Admin\Widgets\Metrics\Bar;
use Illuminate\Http\Request;

class 用户统计 extends Bar
{
    /**
     * 初始化卡片内容
     */
    protected function init()
    {
        parent::init();

        $color = Admin::color();

        $dark35 = $color->dark35();

        // 卡片内容宽度
        $this->contentWidth(5, 7);
        // 标题
        $this->title('用户统计');
        // 设置下拉选项
        $this->dropdown([
            '7' => '近7天',
        ]);
        // 设置图表颜色
        $this->chartColors([
            $dark35,
            $dark35,
            $color->primary(),
            $dark35,
            $dark35,
            $dark35
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
            case '7':
            default:
                // 总人数、今日新增人数
                $user_count = Users::count();
                $new_user_count = Users::whereDate("created_at", date("Y-m-d", time()))->count();
                $yesterday_user_count = Users::whereDate("created_at", date("Y-m-d", time() - 86400))->count();
                $two_day_before_user_count = Users::whereDate("created_at", date("Y-m-d", time() - (86400 * 2)))->count();
                $three_day_before_user_count = Users::whereDate("created_at", date("Y-m-d", time() - (86400 * 3)))->count();
                $four_day_before_user_count = Users::whereDate("created_at", date("Y-m-d", time() - (86400 * 4)))->count();
                $five_day_before_user_count = Users::whereDate("created_at", date("Y-m-d", time() - (86400 * 5)))->count();
                $six_day_before_user_count = Users::whereDate("created_at", date("Y-m-d", time() - (86400 * 6)))->count();
                // 卡片内容
                $this->withContent($user_count, $new_user_count);

                // 图表数据
                $this->withChart([
                    [
                        'name' => '当日新增',
                        'data' => [$six_day_before_user_count, $five_day_before_user_count, $four_day_before_user_count, $three_day_before_user_count, $two_day_before_user_count, $yesterday_user_count, $new_user_count],
                    ],
                ]);
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
     * 设置卡片内容.
     *
     * @param string $title
     * @param string $value
     * @param string $style
     *
     * @return $this
     */
    public function withContent($title, $value, $style = 'success')
    {
        // 根据选项显示
        $label = strtolower(
            $this->dropdown[request()->option] ?? 'last 7 days'
        );

        $minHeight = '183px';

        return $this->content(
            <<<HTML
<div class="d-flex p-1 flex-column justify-content-between" style="padding-top: 0;width: 100%;height: 100%;min-height: {$minHeight}">
    <div class="text-left">
        <h1 class="font-lg-2 mt-2 mb-0">{$title}</h1>
        <h5 class="font-medium-2" style="margin-top: 10px;">
            <span>今天新增: </span>
            <span class="text-{$style}">{$value} </span>
        </h5>
    </div>
</div>
HTML
        );
    }
}
