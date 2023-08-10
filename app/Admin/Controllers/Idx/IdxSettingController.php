<?php

namespace App\Admin\Controllers\Idx;

use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Admin;
use Dcat\Admin\Http\Controllers\AdminController;

use App\Admin\Repositories\Idx\IdxSetting;
use App\Api\Repositories\Idx\IdxSettingRepository;


/**
 * 此控制器用于项目内的各种内容选项，这些内容选项大多是由多条数据、多字段数据 (单字段数据由系统设置和广告设置就可完成)
 * 例如：
 *  汽车品牌，有 中文名、英文名、LOGO等数据；
 *  打赏礼物，有 礼物名、图片、单价等数据；
 *
 * 使用方式：
 *  在设置路由时，一般为 admin/setting/***，其中 *** 即表示当前项目的设置类型，
 *  比如，$router->resource('setting/gift', 'Idx\IdxSettingController');
 *  以上即表示获取字段 type 的数据为 gift 的所有行。
 *  如果有多项分类，则只需要设置多个路由即可。
 *
 *  如果需要添加一类设置，则需要在 /app/Models/Idx/IdxSetting.php 中添加以下格式的方法：
 *      添加方法:
 *          public static function <type>_fields(){
 *              return ['<字段说明>', '<字段说明>', '<字段说明>', .......];
 *          }
 *      修改方法 type_page_attribute():
 *          在返回数组中，添加一个键名为 type 数据的数组，并且值也是一个数组，至少有一个键名为 title 的元素
 *          '<type>'=> [
 *              'title'=> '<标题>',
 *          ],
 */
class IdxSettingController extends AdminController{
    protected int $id;
    protected string $type;
    protected int $delete_allowed;
    protected int $update_allowed;

    protected $setting_type;
    protected $fields_setting;
    protected $title;
    protected $setting_update_allowed;
    protected $setting_delete_allowed;
    protected $setting_create_allowed;
    protected $setting_no_update_ids;
    protected $setting_no_delete_ids;

    /**
     * 设置标题
     */
    public function __construct(){
        $this->setting_type = explode('/', explode('?', Request::getRequestUri())[0])[3];
        $setting = (new IdxSetting())->get_type_page_attribute($this->setting_type);
        $this->title = $setting['title'];
        $this->fields_setting = $setting['fields'];
        $this->setting_update_allowed = $setting['update_allowed'] ?? false;
        $this->setting_delete_allowed = $setting['delete_allowed'] ?? false;
        $this->setting_create_allowed = $setting['create_allowed'] ?? true;
        $this->setting_no_update_ids = $setting['no_update_ids'] ?? [];
        $this->setting_no_delete_ids = $setting['no_delete_ids'] ?? [];
    }

    protected function grid(){
        return Grid::make((new IdxSetting())->model()->with(['testparent'])->where("type", $this->setting_type), function (Grid $grid){
            // $grid->column('id')->sortable();
            $i = 0;
            foreach($this->fields_setting as $field){
                switch($field['input_type']){
                    case "image":
                        $g = $grid->column('value' . strval($i), $field['field_name'])->image('', 60, 60);
                        break;
                    case "multipleImage":
                        $g = $grid->column('value' . strval($i), $field['field_name'])->display(function ($images) {
                            return explode(',', $images);
                        })->image('', 60, 60);
                        break;
                    case "currency":
                        $g = $grid->column("value" . strval($i), $field['field_name'])->display(function() use($i, $field){
                            $field_key = 'value' . strval($i);
                            return $this->$field_key . ' ' . $field['symbol'];
                        });
                        break;
                    case "sort":
                        $g = $grid->model()->orderBy('sort', 'asc');
                        $grid->order->orderable();
                        break;
                    case "switch":
                        $g = $grid->column('value' . strval($i), $field['field_name'])->switch();
                        break;
                    case "select":
                        if(($field['with'] ?? '') != ''){
                            $g = $grid->column($field['with'] . '.' . $field['with_field'], $field['with_field_name']);
                        }else{
                            $g = $grid->column('value'. strval($i), $field['field_name']);
                        }
                        break;
                    case "file":
                        $g = $grid->column('value' . strval($i), $field['field_name'])->limit(20);
                        break;
                    default:
                        $g = $grid->column('value' . strval($i), $field['field_name']);
                }
                // 如果设置了前缀、后缀，会执行此方法进行拼接
                if(($field['prefix'] ?? '') != '' || ($field['suffix'] ?? '') != ''){
                    $prefix = $field['prefix'] ?? '';
                    $suffix = $field['suffix'] ?? '';
                    $g->display(function() use($prefix, $suffix, $i){
                        $field_key = 'value' . strval($i);
                        return $prefix . $this->$field_key . $suffix;
                    });
                }
                // 如果设置了自定义展示方法，那么需要在此实现此方法
                if($field['display'] ?? false){
                    if($field['display'] == 'test'){
                        $g->display(function() use($i){
                            $value_name = 'value' . strval($i);
                            return "这里是自定义展示{$this->$value_name}";
                        });
                    }
                }
                $i += 1;
            }
            // $grid->column('created_at');
            // $grid->column('updated_at')->sortable();
            $setting_delete_allowed = $this->setting_delete_allowed;
            $setting_update_allowed = $this->setting_update_allowed;
            $setting_no_update_ids = $this->setting_no_update_ids;
            $setting_no_delete_ids = $this->setting_no_delete_ids;
            $grid->actions(function (Grid\Displayers\Actions $actions) use($setting_delete_allowed, $setting_update_allowed, $setting_no_delete_ids, $setting_no_update_ids){
                if($this->delete_allowed == 0 || $setting_delete_allowed == false || in_array($this->id, $setting_no_delete_ids)){
                    $actions->disableDelete();
                }
                if($this->update_allowed == 0 || $setting_update_allowed == false || in_array($this->id, $setting_no_update_ids)){
                    $actions->disableEdit();
                }
            });
            if($this->setting_create_allowed == false){
                $grid->disableCreateButton();

            }
            $grid->disableViewButton();
        });
    }

    protected function form(){
        $setting_type = $this->setting_type;
        return Form::make(new IdxSetting(), function (Form $form) use($setting_type){
            $form->hidden('id');
            $form->hidden('type')->value($setting_type);
            // $type_fields = (new IdxSetting())->get_type_fields($setting_type);
            $i = 0;
            foreach($this->fields_setting as $field){
                if(($field['no_update'] ?? false) && $form->isEditing()){
                    $form->display('value' . strval($i), $field['field_name']);
                    $i += 1;
                    continue;
                }
                switch($field['input_type']){
                    case "image":
                        $f = $form->image('value' . strval($i), $field['field_name'])->autoUpload()->uniqueName()->saveFullUrl()->removable(false)->retainable();
                        break;
                    case "select":
                        $f = $form->select('value' . strval($i), $field['field_name'])->options($field['options']);
                        break;
                    case "file":
                        $f = $form->file('value' . strval($i), $field['field_name'])->autoUpload()->uniqueName()->saveFullUrl()->removable(false)->retainable();
                        break;
                    case "multipleImage":
                        $f = $form->multipleImage('value' . strval($i), $field['field_name'])->autoUpload()->uniqueName()->saveFullUrl()->removable(false)->retainable()->saving(function($paths){
                            return implode(',', $paths);
                        });
                        break;
                    case "currency":
                        $f = $form->currency("value" . strval($i), $field['field_name'])->symbol($field['symbol']);
                        break;
                    case "switch":
                        $f = $form->switch('value' . strval($i), $field['field_name']);
                        break;
                    case "number":
                        $f = $form->number('value' . strval($i), $field['field_name']);
                        break;
                    case "sort":
                        $form->hidden('sort', $field['field_name']);
                        if($form->isCreating()){
                            $form->saving(function(Form $form) use($setting_type){
                                $max_sort = (new IdxSetting())->get_type_maxsort($setting_type);
                                $form->sort = $max_sort + 1;
                            });
                        }
                        if($form->isEditing() && $form->_orderable){
                            $res_sort = (new IdxSetting())->get_type_near_data($setting_type, $form->model()->sort, boolval($form->_orderable));
                            (new IdxSetting())->update_sort($setting_type, $form->model()->id, $form->model()->sort, $res_sort);
                            return $form->response()->success('排序成功');
                        }
                        break;
                    default:
                        $f = $form->text('value' . strval($i), $field['field_name']);
                }
                if($field['required'] ?? false){
                    $f->required();
                }
                if($field['help'] ?? false){
                    $f->help($field['help'] ?? null);
                }
                if($field['default'] ?? false){
                    $f->default($field['default'] ?? null);
                }
                $i += 1;
            }
            if(config('admin.developer_mode') && $this->setting_delete_allowed == true){
                $form->switch("delete_allowed", '是否允许删除')->value(1);
            }
            if(config('admin.developer_mode') && $this->setting_update_allowed == true){
                $form->switch("update_allowed", '是否允许修改')->value(1);
            }
            // 清除缓存
            $form->saved(function(Form $form, $result){
                (new IdxSettingRepository())->del_cache($form->type ?? $form->model()->type);
            });
            $form->deleted(function(Form $form, $result){
                $type = $form->model()->toArray()[0]['type'];
                (new IdxSettingRepository())->del_cache($type);
            });

            $form->footer(function ($footer) {
                $footer->disableViewCheck();
                $footer->disableEditingCheck();
                $footer->disableCreatingCheck();
            });
            $form->tools(function (Form\Tools $tools) {
                $tools->disableView();
                $tools->disableDelete();
            });
        });
    }

    public function api_test(HttpRequest $request){
        $q = $request->get('q');
        $data = (new IdxSetting())->model()->where("type", 'test')->where("value0", 'like', '%' . $q . '%')->get(['id', DB::raw("value0 as text")]);
        return $data;
    }
}
