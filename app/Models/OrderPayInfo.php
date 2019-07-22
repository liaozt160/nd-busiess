<?php

namespace App\Models;

use App\Exceptions\BaseException;
use App\Traits\Consts;
use Illuminate\Database\Eloquent\Model;

class OrderPayInfo extends Model
{
    protected $table = 'buyer_order_pay_information';
    protected $fillable = ['order_id','payment','amount','verification'];
    protected $guarded = ['id'];

    public static function addItem($param=[]){
        $m = self::where($param)->first();
        if($m){
            throw new BaseException(Consts::RECORD_EXIST);
        }
        $m = self::create($param);
        if($m){
           return $m;
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }

    public static function delItem($id){
        $del = self::destroy($id);
        if($del){
            return true;
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }

    public static function getList($orderId){
        $list = self::where('order_id',$orderId)->get();
        return  $list;
    }



}
