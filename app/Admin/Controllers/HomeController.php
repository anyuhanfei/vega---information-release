<?php

namespace App\Admin\Controllers;

use App\Admin\Metrics\Examples;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\Dashboard;
use Dcat\Admin\Layout\Column;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->header('仪表盘')
            ->description('')
            ->body(function (Row $row) {
                $row->column(6, function (Column $column) {
                    $column->row(new Examples\用户统计());
                    $column->row(new Examples\活动统计());
                });

                $row->column(6, function (Column $column) {
                    $column->row(new Examples\活动订单统计());
                    $column->row(function (Row $row) {
                        $row->column(6, new Examples\活动类型统计());
                        $row->column(6, new Examples\活动收费统计());
                    });
                    $column->row(function (Row $row) {
                        $row->column(6, new Examples\VIP统计());
                    });
                    

                });
            });
    }
}
