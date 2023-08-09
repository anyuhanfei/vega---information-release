<?php

namespace App\Models\User;

use App\Models\Log\LogUserFund;
use Illuminate\Database\Eloquent\Model;


/**
 * 会员资产表
 */
class UserFunds extends Model{
    public $timestamps = false;
    protected $fillable = ['id'];
}
