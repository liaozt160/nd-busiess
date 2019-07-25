<?php

namespace App\Models;

use App\Traits\Consts;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'buyer_order_detail';
    protected $guarded = ['id'];

    public static function getBusinessWithId($orderId){
        $list = self::select('business_id')->where('order_id',$orderId)->get();
        return $list;
    }

    public static function getBusinessLevel($orderId,$level = Consts::ACCOUNT_ACCESS_LEVEL_TWO){
        $columns = Business::getColumnsByLevel($level);
        $query = self::select($columns)->from('buyer_order_detail as d')
            ->leftjoin('business as b','d.business_id','=','b.id')
            ->leftjoin('business_zh as z','z.business_id','=','b.id')
            ->where('d.order_id',$orderId);
        $list = $query->get();
        return $list;
    }

    public static function getBusinessLevelTwo($orderId){
            return self::getBusinessLevel($orderId,Consts::ACCOUNT_ACCESS_LEVEL_TWO);
    }

}
