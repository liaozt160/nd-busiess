<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Location extends Model
{

    public static function queryChild($code='0',$lang='en')
    {
        if($lang == 'en'){
            $columns = ['code','nameEn as name'];
        }else{
            $columns = ['code','name'];
        }
        $list = self::select($columns)->where('parentId',$code)->get();
        return $list;
    }
}
