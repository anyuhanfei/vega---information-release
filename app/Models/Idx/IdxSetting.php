<?php

namespace App\Models\Idx;

use Dcat\Admin\Traits\ModelTree;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Database\Eloquent\Builder;
use App\Models\BaseFilter;

/**
 * 项目配置表
 */
class IdxSetting extends Model implements Sortable{
	use HasDateTimeFormatter;
    use SoftDeletes;
    use SortableTrait;
    use BaseFilter;

    protected $table = 'idx_setting';
    protected $guarded = [];
    protected $sortable = [
        // 设置排序字段名称
        'order_column_name' => 'sort',
        // 是否在创建时自动排序，此参数建议设置为true
        'sort_when_creating' => false,
    ];

    public function scopeId(Builder $builder, int $value){
        return $builder->where("id", $value);
    }

    public function scopeType(Builder $builder, string $value){
        return $builder->where("type", $value);
    }

    /**
     * 每多一个分类，都要在返回数组中添加一个以分类名为键的元素
     *
     * @return array
     */
    public static function type_page_attribute():array{
        return [
            // '<分类字段名>'=> [
            //     'title'=> '<此分类页面的标题>',
            //     'fields'=> [
            //         [
            //             'field'=> '(必设置项)接口返回数据时的键名',
            //             'field_name'=> '(必设置项)后台列表上本字段的中文名称',
            //             'help'=> "(可选项)表单提示语",
            //             'input_type'=> '(必设置项)本字段数据格式, 可选项: text, image, switch, select, number, sort, multipleImage, currency',
            //             'options'=> '(可选项)如果 input_type 设置为 select 时设置，数据路由地址',
            //             'symbol'=> '(可选项)如果 input_type 设置为 currency 时设置，表示单位名称,
            //             'prefix'=> '(可选项)在后台列表中展示时, 在字段数据前添加的内容',
            //             'suffix'=> '(可选项)在后台列表中展示时, 在字段数据后添加的内容',
            //             'display'=> "(可选项)如果 prefix suffix 项都无法满足要求，可以在此项上设置一个名称，然后在 IdxSettingController 中设置",
            //             'default'=> '(可选项)默认值',
            //             'required'=> '(可选项)布尔值，默认为false，表示不必填',
            //             'no_update'=> "(可选项), 布尔类型, 默认为false, 表示可以修改",
            //             'with'=> "关联, 此设置的名称必须在当前model 中设有同名方法的关联方法",
            //             'with_field'=> '关联, 被关联数据在后台列表上要展示的字段',
            //             'with_field_name'=> '关联, 被关联数据在后台列表上要展示的字段的中文名称'
            //         ],
            //     ],
            //     'update_allowed'=> "(可选项)后台列表页，每行数据的编辑按钮, 默认为false, 表示没有编辑按钮",
            //     'delete_allowed'=> "(可选项)后台列表页，每行数据的删除按钮, 默认为false, 表示没有编辑按钮",
            //     'create_allowed'=> "(可选项)后台列表页的添加按钮控制, 默认为true, 表示有添加按钮",
            //     'no_update_ids'=> "(可选项)数组类型，元素为id值，设置在此的那行数据将没有编辑按钮(也就表示无法编辑)",
            //     'no_delete_ids'=> "(可选项)数组类型，元素为id值，设置在此的那行数据将没有删除按钮(也就表示无法删除)",
            // ],

            "test"=> [
                'title'=> '自定义设置测试',
                'fields'=> [
                    ['field'=> 'identity', 'field_name'=> '标识', 'input_type'=> 'text', 'help'=> '这里是表单提示', 'required'=> true],
                    ['field'=> 'images', 'field_name'=> '图片集', 'input_type'=> 'multipleImage'],
                    ['field'=> 'onoff', 'field_name'=> '开关', 'input_type'=> 'switch', 'default'=> '1'],
                    ['field'=> 'parent', 'field_name'=> '父级', 'input_type'=> 'select', 'options'=> 'api/test', 'with'=> 'testparent', 'with_field'=> 'parent_identity', 'with_field_name'=> '父级标识'],
                    ['field'=> 'content', 'field_name'=> '内容展示', 'input_type'=> 'text', 'prefix'=> '&&&', 'suffix'=> '!!!'],
                    ['field'=> 'content2', 'field_name'=> '内容展示2', 'input_type'=> 'text', 'display'=> 'test']
                ],
                'update_allowed'=> true,
                'delete_allowed'=> true,
                'create_allowed'=> true,
                'no_update_ids'=> [],
                'no_delete_ids'=> [],
            ],
            // 会员预设标签
            'user_tags'=> [
                'title'=> '会员标签管理',
                'fields'=> [
                    ['field'=> 'name', 'field_name'=> '标签名称', 'input_type'=> 'text', 'required'=> true],
                ],
                'update_allowed'=> true,
                'delete_allowed'=> true,
                'create_allowed'=> true,
            ],
            // 预设头像
            'user_avatars'=> [
                'title'=> '预设头像管理',
                'fields'=> [
                    ['field'=> 'avatar', 'field_name'=> '头像', 'input_type'=> 'image', 'required'=> true],
                ],
                'update_allowed'=> false,
                'delete_allowed'=> true,
                'create_allowed'=> true,
            ],
            // 报名信息
            'information_of_registration_key'=> [
                'title'=> '报名信息管理',
                'fields'=> [
                    ['field'=> 'key_name', 'field_name'=> '信息标题', 'input_type'=> 'text', 'required'=> true],
                    ['field'=> 'input_type', 'field_name'=> '内容类型', 'input_type'=> 'select', 'options'=> ['文字'=> '文字:需要填写一段文字', '选项(有或无)'=> '选项:需要选择`有`或`无`', '选项(是或否)'=> '选项:需要选择`是`或`否`'], 'required'=> true],
                    ['field'=> 'is_show', 'field_name'=> '展示', 'input_type'=> 'switch'],
                ],
                'update_allowed'=> true,
                'delete_allowed'=> true,
                'create_allowed'=> true,
            ],
            // 会员购买 （只有包年、包季、包月）
            'vip'=> [
                'title'=> 'VIP管理',
                'fields'=> [
                    ['field'=> 'name', 'field_name'=> "名称", 'input_type'=> 'text', 'no_update'=> true, 'required'=> true],
                    ['field'=> 'price', 'field_name'=> "支付价格", 'input_type'=> 'currency', 'symbol'=> '元', 'required'=> true],
                    ['field'=> 'old_price', 'field_name'=> "原价", 'input_type'=> 'currency', 'symbol'=> '元', 'required'=> true],
                    ['field'=> 'discount', 'field_name'=> "折扣比例", 'input_type'=> 'currency', 'symbol'=> '%', 'help'=> "如：设置为 20%，那么用户发布活动时为 20 * (1 - 0.2) = 16元", 'required'=> true],
                    ['field'=> "time_limit", 'field_name'=> "时限", 'input_type'=> 'number', 'help'=> "单位为 天数", 'required'=> true],
                ],
                'update_allowed'=> true,
                'delete_allowed'=> false,
                'create_allowed'=> false,
            ]
        ];
    }

    /**
     * 如果是关联表自己，则需要将 value0 等字段名修改为正确的别名（为了接口查询时能正确获取数据，而不是获取数据后还要整理数据）
     *
     * @return void
     */
    public function testparent(){
        return $this->hasOne(self::class, 'id', 'value3')->select(['id', 'value0 as parent_identity']);
    }
}
