<?php

namespace App\Models;

use App\Exceptions\BaseException;
use App\Traits\Consts;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $m = self::where($param)->whereNull('buyer_deleted_at')->first();
        if($m){
            throw new BaseException(Consts::RECORD_EXIST);
        }
        $m = self::create($param);
        if($m){
            return $m;
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }

    public static function getList($param,$accountId){
        $query = self::from('attention_to_business as t')
            ->select(['t.id','b.company','b.title','b.listing','b.price','b.status','t.business_id','a.name','t.account_id','s.buyer','t.buyer_id','t.created_at']);
        $query->leftjoin('accounts as a','t.account_id','=','a.id')
        ->leftjoin('business as b','t.business_id','=','b.id')
        ->leftjoin('buyer as s','t.buyer_id','=','s.id')
        ->whereNull('buyer_deleted_at');
        // filter
        if(isset($param['status']) && $param['status']){
            $query->where('b.status',$param['status']);
        }
        if(isset($param['recommend_by_me']) && $param['recommend_by_me']){
            $query->where('t.account_id',$accountId);
        }

        if(isset($param['q']) && $param['q']){
            $q = $param['q'];
            $query->where(DB::raw("concat(title,listing)"),'like','%'.$q.'%');
        }

        $list = $query->paginate(15);
        return $list;
    }


    public static function getListByBuyer($accountId,$param=[]){
//        DB::enableQueryLog();
        $query = self::from('attention_to_business as t')
            ->select(['t.id','b.company','b.title','b.listing','b.price','b.status','t.business_id','a.name','t.account_id','s.buyer','t.buyer_id','t.created_at']);
        $query->leftjoin('accounts as a','t.account_id','=','a.id')
            ->leftjoin('business as b','t.business_id','=','b.id')
            ->leftjoin('buyer as s','t.buyer_id','=','s.id');
        $query->where('t.account_id',$accountId)->whereNull('buyer_deleted_at');

        // filter
        if(isset($param['status']) && $param['status']){
            $query->where('b.status',$param['status']);
        }
        if(isset($param['q']) && $param['q']){
            $q = $param['q'];
            $query->where(DB::raw("concat(title,listing)"),'like','%'.$q.'%');
        }
        $list = $query->paginate(15);
//        Log::debug(DB::getQueryLog());
        return $list;
    }


    public static function getListByBusiness($accountId){
//        DB::enableQueryLog();
        $query = self::from('attention_to_business as t')
            ->select(['t.id','b.company','b.title','t.business_id','a.name','t.account_id','s.buyer','s.funds_available','s.funds_verified','t.buyer_id','t.created_at']);
        $query->join('accounts as a','t.account_id','=','a.id')
            ->join('business as b','t.business_id','=','b.id')
            ->leftjoin('buyer as s','t.buyer_id','=','s.id');
        $query->whereNull('t.business_deleted_at')->whereNull('t.buyer_deleted_at');
        $query->whereRaw('nd_b.business_broker = '.$accountId);
        $list = $query->paginate(15);
//        var_dump(DB::getQueryLog());
        return $list;
    }


    public static function delItemByBuyer($ids){
        $update = self::whereIn('id',$ids)
            ->update(['buyer_deleted_at' => new Carbon(),'business_deleted_at'=>new Carbon()]);
        if($update){
            return $update;
        }
        throw new BaseException(Consts::NO_RECORD_FOUND);
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

    public function account(){
        return $this->hasOne('App\Models\Account','id','account_id');
    }

    public function business(){
        return $this->hasOne('App\Models\Business','id','business_id');
    }


    /**  卖家关注汇总
     * User: Tank
     * Date: 2019/6/20
     * Time: 19:14
     */
    public static function getListSumByBusiness($accountId=null){
        $query = self::from('attention_to_business as a')
            ->join('business as b','b.id','=','a.business_id')
            ->whereNull('a.buyer_deleted_at')
            ->whereNull('a.business_deleted_at');
        if($accountId){
            $query->where('b.business_broker',$accountId);
        }
        return $query->count();
    }

    public static function getListSumByBuyer($accountId=null){
        $query = self::from('attention_to_business')
            ->whereNull('buyer_deleted_at')
            ->whereNull('business_deleted_at');
        if($accountId){
            $query->where('account_id',$accountId);
        }
        return $query->count();
    }



}
