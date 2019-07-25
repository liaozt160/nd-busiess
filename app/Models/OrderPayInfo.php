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
            if($m->payment == 2){
                $order = $m->order;
                if($order){
                    $order->status = 5;
                    $order->paid = 2;
                    $order->save();
                }
            }else{
                $order = $m->order;
                if($order){
                    $order->status = 1;
                    $order->paid = 1;
                    $order->save();
                }
            }
           return $m;
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }

    public static function delItem($id){
        $m = self::where('id',$id)->whereNull('verification')->first();
        if(!$m){
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        $order = $m->order;
        if($order){
            if($m->payment == 2){
                $order->paid=1;
                $order->status = 2;
                $order->save();
            }
        }
        $m->delete();
        return true;
    }

    public static function getList($orderId){
        $list = self::where('order_id',$orderId)->get();
        return  $list;
    }

    public function order(){
        return $this->belongsTo('App\Models\Order','order_id','id');
    }


}
