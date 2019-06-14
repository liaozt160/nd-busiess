<?php

namespace App\Models;

use App\Exceptions\BaseException;
use App\Traits\Consts;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BusinessAttention extends Model
{
    protected $table = 'attention_to_business';
    protected $guarded = ['id'];



    public static function addItem($businessId,$accountId,$buyerId){
         $m = self::create(['business_id'=>$businessId,'account_id' => $accountId,'buyer_id'=>$buyerId]);
         if($m){
             return $m;
         }
         throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }

    public static function addItemByArray($param){
        $m = self::create($param);
        if($m){
            return $m;
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }

    public static function getList(){
        $query = self::from('attention_to_business as t')
            ->select(['b.title','t.business_id','a.name','t.account_id','s.buyer','t.buyer_id','t.created_at']);
        $query->leftjoin('accounts as a','t.account_id','=','a.id')
        ->leftjoin('business as b','t.business_id','=','b.id')
        ->leftjoin('buyer as s','t.buyer_id','=','s.id');
        $list = $query->paginate(15);
        return $list;
    }


    public static function getListByBuyer($accountId){
        $query = self::from('attention_to_business as t')
            ->select(['b.title','t.business_id','a.name','t.account_id','s.buyer','t.buyer_id','t.created_at']);
        $query->leftjoin('accounts as a','t.account_id','=','a.id')
            ->leftjoin('business as b','t.business_id','=','b.id')
            ->leftjoin('buyer as s','t.buyer_id','=','s.id');
        $query->where('t.account_id',$accountId)->whereNull('buyer_deleted_at');
        $list = $query->paginate(15);
        return $list;
    }


    public static function getListByBusiness($accountId){
        $query = self::from('attention_to_business as t')
            ->select(['b.title','t.business_id','a.name','t.account_id','s.buyer','t.buyer_id','t.created_at']);
        $query->leftjoin('accounts as a','t.account_id','=','a.id')
            ->leftjoin('business as b','t.business_id','=','b.id')
            ->leftjoin('buyer as s','t.buyer_id','=','s.id');
        $query->where('b.business_broker',$accountId)->whereNull('business_deleted_at');
        $list = $query->paginate(15);
        return $list;
    }


    public static function delItemByBuyer($id){
        $m = self::find($id);
        if(!$m){
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        $m->buyer_deleted_at = new Carbon();
        if($m->save()){
            return $m;
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }

    public static function delItemByBusiness($id){
        $m = self::find($id);
        if(!$m){
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        $m->business_deleted_at = new Carbon();
        if($m->save()){
            return $m;
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }
}
