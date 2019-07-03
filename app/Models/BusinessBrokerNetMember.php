<?php

namespace App\Models;

use App\Exceptions\BaseException;
use App\Traits\Consts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public static function getMember($netId){
        $list = self::where('net_id',$netId)->with(['account:id,name'])->get();
        return $list;
    }

    public static function setManager($id,$status = 0){
        $update = self::where(['id' => $id])->update(['manager' => $status]);
        if($update){
            return $update;
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }

    public static function getFreeBusinessBroker(){
//         DB::enableQueryLog();
         $query = DB::table('accounts as a')->select(['id as key','name as label']);
         $query->whereNotExists(function ($subQuery){
             $subQuery->select('account_id as id')->from('business_broker_net_member as m')
             ->whereRaw('nd_m.account_id = nd_a.id');
         });
         $list = $query->get();
//         var_dump(DB::getQueryLog());
         return $list;
    }

    public function account(){
        return $this->hasOne('App\Models\Account','id','account_id');
    }

    public static function getAccountIdByManager($accountId){
        if($accountId === null ){
            $query = self::from('accounts as a')
                ->select(['a.id','a.name'])
                ->where('role',Consts::ACCOUNT_ROLE_BUSINESS_BROKER)
                ->whereNull('deleted_at');
            $list = $query->get();
            return $list;
        }
        $m = self::find($accountId);
        if(!$m){
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        if($m->manager != 1) {
            $accounts = ['account_id' => $accountId, 'name' => 'my self'];
            return $accounts;
        }
        $accounts = self::select(['a.account_id','a.name'])->where('net_id',$m->net_id)->where('manager',1)->get()->toArray();
        $accounts = array_column($accounts,'account_id');
        $query = self::from('business_broker_net_member as m')
            ->select(['a.id','a.name'])
            ->join('accounts as a','a.id','m.account_id')
            ->whereIn('a.account_id',$accounts);
        $list = $query->get();
        return $list;
    }

}
