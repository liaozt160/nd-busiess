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
        $list = self::select($columns)->where('parentId',$code)->orderBy('name')->get();
        return $list;
    }

    public static function getLocationByCode($code,$lang='en'){
        $column = $lang=='en'?'CONCAT(MergerNameEn," ",nameEn) as location':'CONCAT(MergerName," ",name) as location';
        $location = self::select(DB::raw($column))->where('code',$code)->first();
        return $location;
    }
}
