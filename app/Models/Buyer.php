<?php

namespace App\Models;

use App\Exceptions\BaseException;
use App\Traits\Consts;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Buyer extends Model
{
    protected $guarded = ['id'];
    protected $table = 'buyer';
    public static function addItem($param){
        $m = new self();
        $m->fill($param);
        if($m->save()){
            return $m;
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }

    public static function updateItem($id,$param){
        $m = self::find($id);
        if(!$m){
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        $m->fill($param);
        if($m->save()){
            return $m;
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }


    public static function deleteItem($id){
        $m = self::find($id);
        if(!$m){
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        $m->deleted_at = new Carbon();
        if($m->save()){
            return $m;
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }

    public static function listItem($param,$accountId=null){
        $query  = self::whereNull('deleted_at');

        if (isset($param['broker_id']) && $param['broker_id'] != 0) {
            $query->where('buyer_broker', $param['broker_id']);
        } else {
            if ($accountId) {
                $accountIds = BuyerBrokerNetMember::getAccountIdByManager($accountId);
                if (is_object($accountIds)) {
                    $accountIds = $accountIds->all();
                }
                $accountIds = array_column($accountIds, 'account_id');
                $query->whereIn('buyer_broker', $accountIds);
            }
        }

        if(isset($param['q']) && isset($param['q'])){
            $query->where(DB::raw("concat(email,phone,buyer)"),'like','%'.$param['q'].'%');
        }

        if(isset($param['funds_available_from']) && $param['funds_available_from']){
            $query->where('funds_available', '>=', $param['funds_available_from']);
        }

        if(isset($param['funds_available_to']) && $param['funds_available_to']){
            $query->where('funds_available' , '<=', $param['funds_available_to']);
        }

        if(isset($param['funds_verified']) && $param['funds_verified']){
            $query->where('funds_verified' ,$param['funds_verified']);
        }
        $list = $query->with('account:id,name,role')->paginate(15);
        return $list;
        $list = $query->paginate(15);
    }


    public static function queryAll($accountId,$q=null,$column=[]){
        $query = self::select($column)
            ->where('status',Consts::BUSINESS_STATUS_NORMAL)->whereNull('deleted_at');
        if($accountId){
            $query->where('buyer_broker',$accountId);
        }
        if($q){
            $query->where('title','like','%'.$q.'%');
        }
        return $query->get();
    }



    public static function accessCheck($id,$user){
        $m = self::find($id);
        if(!$m){
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        if($user->role == Consts::ACCOUNT_ROLE_ADMIN){
            return true;
        }
        if($user->id == $m->buyer_broker){
            return true;
        }

        $list = BuyerBrokerNetMember::getAccountIdByManager($user->id);
        if (is_object($list)) {
            $list = $list->all();
        }
        $accountIds = array_column($list, 'account_id');
        if (in_array($m->buyer_broker, $accountIds)) {
            return true;
        }

        throw new BaseException(Consts::ACCOUNT_ACCESS_DENY);
    }

    public function account(){
        return $this->hasOne('App\Models\Account','id','buyer_broker');
    }

    public static function buyerSum($accountId=null,$status=null){
        $query = self::whereNull('deleted_at');
        if($accountId){
            $query->where('buyer_broker',$accountId);
        }
        if($status){
            $query->where('status',$status);
        }
        return $query->count();
    }

    public static function setServicePay($id,$status){
        $m = self::find($id);
        if(!$m){
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        $m->services_pay = $status;
        if($m->save()){
            return $m;
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }

    public static function changeBroker($buyerId,$accountId){
        $r = self::where('id',$buyerId)->update(['buyer_broker' => $accountId]);
        return $r;
    }


    public static function buyerBrokerQuery($q,$accountId){
        $accounts = BuyerBrokerNetMember::getAccountIdByManager($accountId);
        if(is_object($accounts)){
            $accounts = $accounts->all();
        }
        return $accounts;
    }

}
