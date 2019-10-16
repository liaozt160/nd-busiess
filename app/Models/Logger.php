<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logger extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;
//    protected $table = 'logs';

    public static function getListByAccount($account = null,$param = []){
        $query = self::select();
        if($account){
            $query->where('user_id');
        }
    }
}
