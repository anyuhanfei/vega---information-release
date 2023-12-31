<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::post('upload', [\App\Api\Controllers\ToolsController::class, 'upload']);
Route::post('send/sms', [\App\Api\Controllers\ToolsController::class, 'send_sms']);
Route::post('test', [\App\Api\Controllers\BaseController::class, 'test']);

// 登录注册
Route::post('register/phone', [\App\Api\Controllers\LoginController::class, 'phone_register']);
Route::post('login/phone', [\App\Api\Controllers\LoginController::class, 'phone_password_login']);
Route::post('login/forget', [\App\Api\Controllers\LoginController::class, 'forget_password']);

Route::post('login/phone_smscode', [\App\Api\Controllers\LoginController::class, 'phone_smscode_login']);
Route::post('login/yidun_oauth', [\App\Api\Controllers\LoginController::class, 'yidun_oauth_login']);

// 系统设置
Route::post('sys/banner', [\App\Api\Controllers\SysController::class, 'banner']);
Route::post('sys/notice', [\App\Api\Controllers\SysController::class, 'notice']);
Route::post('sys/notice/list', [\App\Api\Controllers\SysController::class, 'notice_list']);
Route::post('sys/ad', [\App\Api\Controllers\SysController::class, 'ad']);
Route::post("sys/{type}", [\App\Api\Controllers\SysController::class, 'idx_setting']);

// 文章
Route::post('sys/article/category', [\App\Api\Controllers\SysController::class, 'article_category_list']);
Route::post('sys/article/list', [\App\Api\Controllers\SysController::class, 'article_list']);
Route::post('sys/article/detail', [\App\Api\Controllers\SysController::class, 'article_detail']);

// 第三方支付的回调接口
Route::match(['get', 'post'], 'wxpay/notify', [\App\Api\Controllers\PayController::class, 'wxpay_notify']);
Route::match(['get', 'post'], 'alipay/notify', [\App\Api\Controllers\PayController::class, 'alipay_notify']);
Route::match(['get', 'post'], 'iospay/notify', [\App\Api\Controllers\PayController::class, 'iospay_notify']);

Route::group([
    'middleware' => ['user.token'],
], function(Router $router){
    // 会员详情
    $router->post('user/detail', [\App\Api\Controllers\UserController::class, 'detail']);
    $router->post('user/update/data', [\App\Api\Controllers\UserController::class, 'update_data']);

    // 会员资产记录、系统消息
    $router->post('user/log/fund', [\App\Api\Controllers\UserLogController::class, 'fund_log']);
    $router->post('test/fund', [\App\Api\Controllers\UserLogController::class, 'test_fund']);
    $router->post('user/log/sysmessage', [\App\Api\Controllers\UserLogController::class, 'sys_message_log']);
    $router->post('user/log/sysmessage/detail', [\App\Api\Controllers\UserLogController::class, 'sys_message_detail']);
    $router->post('user/withdraw', [\App\Api\Controllers\UserLogController::class, 'withdraw']);
    $router->post('user/withdraw/log', [\App\Api\Controllers\UserLogController::class, 'withdraw_log']);

    // 会员密码
    $router->post('user/update_password', [\App\Api\Controllers\UserController::class, 'update_password']);
    $router->post('user/forget_password', [\App\Api\Controllers\UserController::class, 'forget_password']);

    // 会员社交
    $router->post('user/set/tag', [\App\Api\Controllers\UserSocialController::class, 'tag_set']);
    $router->post('user/tag/like', [\App\Api\Controllers\UserSocialController::class, 'tag_like']);
    $router->post('user/attention/list', [\App\Api\Controllers\UserSocialController::class, 'attention_list']);
    $router->post('user/attention', [\App\Api\Controllers\UserSocialController::class, 'attention']);
    $router->post('user/fans/list', [\App\Api\Controllers\UserSocialController::class, 'fans_list']);
    $router->post('user/album/add', [\App\Api\Controllers\UserSocialController::class, 'album_add']);
    $router->post('user/album/del', [\App\Api\Controllers\UserSocialController::class, 'album_del']);
    $router->post('user/album/list', [\App\Api\Controllers\UserSocialController::class, 'album_list']);

    // 会员其他
    $router->post('user/vip/pay', [\App\Api\Controllers\PayController::class, 'vip']);
    $router->post('user/coordinate', [\App\Api\Controllers\UserController::class, 'set_coordinate']);
    $router->post('user/credit', [\App\Api\Controllers\UserController::class, 'credit']);
    $router->post('auto/vip/check', [\App\Api\Controllers\UserController::class, 'auto_vip_check']);

    $router->post('user/restart', [\App\Api\Controllers\UserController::class, 'restart']);
    $router->post('user/write/off', [\App\Api\Controllers\UserController::class, 'write_off']);

    // 活动
    $router->post('release/price', [\App\Api\Controllers\SysController::class, 'release_price']);
    $router->post('event/category', [\App\Api\Controllers\EventController::class, 'event_category']);
    $router->post('event/create', [\App\Api\Controllers\EventController::class, 'event_create']);
    $router->post('event/update', [\App\Api\Controllers\EventController::class, 'event_update']);
    $router->post('event/list', [\App\Api\Controllers\EventController::class, 'event_list']);
    $router->post('event/detail', [\App\Api\Controllers\EventController::class, 'event_detail']);
    $router->post('event/qa/add', [\App\Api\Controllers\UserSocialController::class, 'qa_add']);
    $router->post('event/qa/list', [\App\Api\Controllers\UserSocialController::class, 'qa_list']);
    $router->post('event/other/list', [\App\Api\Controllers\EventController::class, 'other_event_list']);
    $router->post('event/user/list', [\App\Api\Controllers\EventController::class, 'user_event_list']);
    $router->post('event/user/cancel', [\App\Api\Controllers\EventController::class, 'user_event_cancel']);
    $router->post('event/user/detail', [\App\Api\Controllers\EventController::class, 'user_event_detail']);
    
    // 活动订单
    $router->post('event/order/create', [\App\Api\Controllers\EventOrderController::class, 'order_create']);
    $router->post('event/order/list', [\App\Api\Controllers\EventOrderController::class, 'event_orders']);
    $router->post('event/order/audit', [\App\Api\Controllers\EventOrderController::class, 'event_order_audit']);
    $router->post('event/other/order', [\App\Api\Controllers\EventOrderController::class, 'other_orders']);
    $router->post('event/user/order', [\App\Api\Controllers\EventOrderController::class, 'user_orders']);
    $router->post('event/user/order/detail', [\App\Api\Controllers\EventOrderController::class, 'user_order_detail']);
    $router->post('event/user/order/cancel', [\App\Api\Controllers\EventOrderController::class, 'user_order_cancel']);
    $router->post('event/user/order/evaluate', [\App\Api\Controllers\EventOrderController::class, 'user_order_evaluate']);
    $router->post('event/user/order/feedback', [\App\Api\Controllers\EventOrderController::class, 'user_order_feedback']);

});

