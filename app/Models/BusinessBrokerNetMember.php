<?php

namespace App\Models;

use App\Exceptions\BaseException;
use App\Traits\Consts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BusinessBrokerNetMember extends Model
{
    protected $fillable = [
        'net_id', 'account_id'
    ];
    protected $guarded = ['id'];
    protected $table = 'business_broker_net_member';


    public static function getExist($netId)
    {
        $list = self::select(['account_id','manager'])->where('net_id', $netId)->get();
        return $list;
    }

    public static function getMember($netId)
    {
        $list = self::where('net_id', $netId)->with(['account:id,name'])->get();
        return $list;
    }

    public static function setManager($id, $status = 0)
    {
        $update = self::where(['id' => $id])->update(['manager' => $status]);
        if ($update) {
            return $update;
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }

    public static function getFreeBusinessBroker($netId =null)
    {
        //  一个中介能属于多个中介网络！
        // a business broker cant only belong to multiple business broker net
        if($netId === null){
            $query = self::from('accounts')
                ->select(['id as key', 'name as label'])
                ->where('role', Consts::ACCOUNT_ROLE_BUSINESS_BROKER)
                ->whereNull('deleted_at');
            $list = $query->get();
            return $list;
        }
//         DB::enableQueryLog();  //  一个中介只能属于一个中介网络！
        // a business broker cant only belong to one business broker net
        $exist = DB::table('accounts as a')->select(['id as key', 'name as label'])
            ->whereExists(function ($subQuery) use ($netId) {
                $subQuery->select('account_id as id')->from('business_broker_net_member as m')
                    ->where('net_id',$netId)
                    ->whereRaw('nd_m.account_id = nd_a.id');
            })->whereNull('deleted_at');

        $query = DB::table('accounts as a')->select(['id as key', 'name as label']);
        $query->whereNotExists(function ($subQuery) {
            $subQuery->select('account_id as id')->from('business_broker_net_member as m')
                ->whereRaw('nd_m.account_id = nd_a.id');
        });
        $query->where('role', Consts::ACCOUNT_ROLE_BUSINESS_BROKER)->whereNull('deleted_at');
        $list = $query->union($exist)->get();
//         var_dump(DB::getQueryLog());
        return $list;
    }

    public function account()
    {
        return $this->hasOne('App\Models\Account', 'id', 'account_id');
    }

    public static function getAccountIdByManager($accountId)
    {
        //admin role
        if ($accountId === null) {
            $query = self::from('accounts as a')
                ->select(['a.id as account_id', 'a.name'])
                ->where('role', Consts::ACCOUNT_ROLE_BUSINESS_BROKER)
                ->whereNull('deleted_at');
            $list = $query->get();
            return $list;
        }

        // don't belong to any broke net manage role
        $m = self::select(['account_id','net_id','manager'])->where('account_id', $accountId)->get();
        if ($m->isEmpty()) {
            $accounts = [['account_id' => $accountId, 'name' => 'my self']];
            return $accounts;
        }
        $netids = $m->map(function ($item,$key){
            if($item->manager == 1){
                return $item->net_id;
            }
        });
        // find all broker net member account id by manager
        $accounts = self::select(['account_id'])->whereIn('net_id', $netids)->get()->toArray();
        $accounts = array_column($accounts, 'account_id');
        $query = self::from('business_broker_net_member as m')
            ->select(['a.id as account_id', 'a.name'])
            ->join('accounts as a', 'a.id', 'm.account_id')
            ->whereIn('m.account_id', $accounts);
        $list = $query->get();
        return $list;
    }

}
