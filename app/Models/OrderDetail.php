<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'buyer_order_detail';
    protected $guarded = ['id'];

    public static function getBusiness($orderId){
        $list = self::select('business_id')->where('order_id',$orderId)->get();
        return $list;
    }
}
