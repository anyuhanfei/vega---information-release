<?php

namespace App\Models\Sys;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * 图床表
 */
class SysImg extends Model{
	use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'sys_img';
    
}
