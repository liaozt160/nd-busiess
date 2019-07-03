<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessBrokerNetMember extends Model
{
    protected $fillable = [
       'net_id','account_id'
    ];
    protected $guarded = ['id'];
    protected $table = 'business_broker_net_member';


    public static function getExist($netId){
        $list = self::select(['account_id'])->where('net_id',$netId)->get();
        return $list;
    }
}
