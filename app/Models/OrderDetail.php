<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'buyer_order_detail';
    protected $guarded = ['id'];

    public static function getBusinessWithId($orderId){
        $list = self::select('business_id')->where('order_id',$orderId)->get();
        return $list;
    }

    public static function getBusinessLevelTwo($orderId){
        $columns = ['b.id', 'b.listing', 'b.title','b.company', 'b.price', 'b.employee_count','b.profitability'
            ,'b.country','b.states','b.city','b.address','b.real_estate','b.building_sf','b.gross_income',
            'b.value_of_real_estate','b.net_income','b.lease','b.lease_term','b.ebitda','b.ff_e','b.inventory','b.commission','b.buyer_financing', 'b.status'];
        $query = self::select($columns)->from('buyer_order_detail as d')
            ->leftjoin('business as b','d.business_id','=','b.id')
            ->where('d.order_id',$orderId);
        $list = $query->get();
        return $list;
    }
}
