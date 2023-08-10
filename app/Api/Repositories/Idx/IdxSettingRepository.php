<?php
namespace App\Api\Repositories\Idx;

use App\Models\Idx\IdxSetting as Model;
use Illuminate\Support\Facades\Cache;

class IdxSettingRepository{
    protected $eloquentClass = Model::class;

    /**
     * 获取设置类型的名称
     *
     * @param string $type
     * @return string
     */
    public function get_type_name(string $type):string{
        return $this->eloquentClass::type_page_attribute()[$type]['title'];
    }

    /**
     * 获取某个设置类型中全部数据
     *
     * @param string $type
     * @return void
     */
    public function use_type_get_datas(string $type){
        return Cache::tags(["idxset:{$type}"])->remember("idxset:{$type}", 86400, function() use($type){
            $data = $this->eloquentClass::type($type)->get();
            $this->reduction_datas($data, $type);
            return $data;
        });
    }

    /**
     * 整理数据
     * 查询到的数据字段名都是 value0、value1等，需要修改为有意义的名称
     * 一些特殊数据需要整理
     *
     * @param \Illuminate\Database\Eloquent\Collection $datas
     * @param string $type
     * @return void
     */
    public function reduction_datas(\Illuminate\Database\Eloquent\Collection &$datas, string $type){
        $fields_set = $this->eloquentClass::type_page_attribute()[$type];
        foreach($datas as &$data){
            // 将 value 中的数据根据设置的键值对组合
            foreach($fields_set['fields'] as $key=> $field_set){
                $key_name = $field_set['field'];
                $value_key = 'value' . $key;
                $data->$key_name = $data->$value_key;
                // 对一些特殊类型的数据进行数据修正
                if($field_set['input_type'] == 'multipleImage'){  // 多图片
                    $data->$key_name = comma_str_to_array($data->$value_key);
                }
                if($field_set['input_type'] == 'select' && !empty($field_set['with'])){  // 有关联数据的
                    $join_function = $field_set['with'];
                    $data->$join_function;
                }
            }
            // 删除无用数据
            unset($data->created_at, $data->updated_at, $data->deleted_at, $data->update_allowed, $data->delete_allowed);
            foreach(range(0, 20) as $v){
                $key_name = 'value' . $v;
                unset($data->$key_name);
            }
        }
    }

    public function del_cache(string $type){
        Cache::tags("idxset:{$type}")->flush();
    }

    /**
     * 随机获取指定数量的预设头像
     *
     * @param integer $number
     * @return void
     */
    public function random_get_user_avatars_list(int $number = 4){
        $data = $this->use_type_get_datas("user_avatars")->toArray();
        $keys = array_rand($data, $number);
        $res = [];
        foreach($keys as $key){
            $res[] = $data[$key];
        }
        return $res;
    }
}