<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Dcat\Admin\Admin;

Admin::routes();

Route::group([
    'prefix'     => config('admin.route.prefix'),
    'namespace'  => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('users', 'UserController');
    $router->resource('user/album', 'User\UserAlbumController');
    $router->resource('user/tag', 'User\UserTagController');
    $router->get("get/users", "UserController@get_users");

    $router->resource('article/category', 'Article\ArticleCategoryController');
    $router->resource('article/tag', 'Article\ArticleTagController');
    $router->resource('article', 'Article\ArticleController');

    $router->resource('sys/notice', 'Sys\SysNoticeController');
    $router->resource('sys/banner', 'Sys\SysBannerController');
    $router->resource('sys/sysad', 'Sys\SysAdController');
    $router->resource('sys/setting', 'Sys\SysSettingController');
    $router->resource('sys/setting/tab', 'Sys\SysSettingController@tab');
    $router->resource('sys/img', 'Sys\SysImgController');

    $router->resource('log/userfund', 'Log\LogUserFundController');
    $router->resource('log/sysmessage', 'Log\LogSysMessageController');
    $router->resource('log/vip', 'Log\LogUserVipController');
    $router->resource('log/feedback', 'Log\LogFeedbackController');

    $router->resource('setting/test', 'Idx\IdxSettingController');
    $router->resource('setting/user_tags', 'Idx\IdxSettingController');
    $router->resource('setting/user_avatars', 'Idx\IdxSettingController');
    $router->resource('setting/information_of_registration_key', 'Idx\IdxSettingController');
    $router->resource('setting/vip', 'Idx\IdxSettingController');
    $router->resource('setting/credit_level', 'Idx\IdxSettingController');
    $router->get('api/test', 'Idx\IdxSettingController@api_test');

    $router->resource('event/category', 'Event\EventCategoryController');
    $router->resource('event/events', 'Event\EventsController');
    $router->resource('event/order', 'Event\EventOrderController');
    $router->resource('event/qa', 'Event\EventQaController');
    $router->resource('event/orderevaluate', 'Event\EventOrderEvaluateController');
    $router->get('api/category', 'Event\EventCategoryController@api_category');


});
