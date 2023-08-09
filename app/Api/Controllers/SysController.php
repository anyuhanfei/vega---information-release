<?php
namespace App\Api\Controllers;

use Illuminate\Http\Request;

use App\Api\Controllers\BaseController;
use App\Api\Services\SysService;

/**
 * 系统相关配置
 */
class SysController extends BaseController{
    protected $service;

    public function __construct(Request $request, SysService $SysService){
        parent::__construct($request);
        $this->service = $SysService;
    }

    /**
     * 获取轮播图数据，可传入位置
     *
     * @return void
     */
    public function banner(\App\Api\Requests\Sys\BannerRequest $request){
        $site = $request->input("site", "首页") ?? '首页';
        return success('轮播图', $this->service->get_banners($site));
    }

    /**
     * 获取公告列表
     *
     * @param Request $request
     * @return void
     */
    public function notice_list(\App\Api\Requests\PageRequest $request){
        $page = $request->input("page", 1) ?? 1;
        $limit = $request->input("limit", 10) ?? 10;
        $data = $this->service->get_notice_list($page, $limit);
        return success('公告列表', $data);
    }

    /**
     * 获取公告详情
     *
     * @param Request $request
     * @return void
     */
    public function notice(Request $request){
        $id = $request->input('id', 0) ?? 0;
        return success('公告', $this->service->get_notice($this->uid, $id));
    }


    /**
     * 获取指定广告，如果是广告位则获取广告位下所有广告
     *
     * @param Request $request
     * @return void
     */
    public function ad(Request $request){
        $id = $request->input('id', 0);
        return success('广告', $this->service->get_ad($id));
    }

    /**
     * 获取文章分类列表
     *
     * @param Request $request
     * @return void
     */
    public function article_category_list(){
        $data = $this->service->get_article_category_list();
        return success("文章分类列表", $data);
    }

    /**
     * 获取文章列表, 可根据文章分类筛选
     *
     * @param \App\Api\Requests\PageRequest $request
     * @return void
     */
    public function article_list(\App\Api\Requests\PageRequest $request){
        $page = $request->input("page", 1) ?? 1;
        $limit = $request->input("limit", 10) ?? 10;
        $category_id = $request->input("category_id", 0) ?? 0;
        $data = $this->service->get_article_list($category_id, $page, $limit);
        return success("文章列表", $data);
    }

    /**
     * 获取文章详情
     *
     * @param Request $request
     * @return void
     */
    public function article_detail(Request $request){
        $id = $request->input('id', 0) ?? 0;
        $data = $this->service->get_article_detail($id);
        return success("文章详情", $data);
    }

    /**
     * 获取项目设置
     *
     * @param Request $request
     * @param [type] $type
     * @return void
     */
    public function idx_setting(Request $request, $type){
        $res = $this->service->get_setting_list($type);
        return success($res['msg'], $res['data']);
    }
}
