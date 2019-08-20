<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessCategory extends Model
{
    protected $table = 'category';

    public static function getQuery($lang = 'en'){
        $columns = ['id','category_'.$lang .' as category'];
        $list = self::select($columns)->get();
        return $list;
    }

}
