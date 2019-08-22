<?php

namespace App\Models;

use App\Exceptions\BaseException;
use App\Traits\Consts;
use Illuminate\Database\Eloquent\Model;

class BuyerBrokerNetMember extends Model
{
    protected $fillable = [
        'net_id', 'account_id'
    ];
    protected $guarded = ['id'];
    protected $table = 'buyer_broker_net_member';


    public static function getExist($netId)
    {
        $list = self::select(['account_id','manager'])->where('net_id', $netId)->get();
        return $list;
    }

    public static function getMember($netId)
    {
        $list = self::where('net_id', $netId)->with(['account:id,name'])->get();
        $list->transform(function ($item, $key) {
            if($item->manager == 1){
                $item->manager =true;
            }else{
                $item->manager =false;
            }
            return $item;
        });
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

    public static function getFreeBuyerBroker($netId =null)
    {
        //  一个中介能属于多个中介网络！ 管理员
        // a business broker cant only belong to multiple business broker net
        $query = self::from('accounts')
            ->select(['id as key', 'name as label'])
            ->where('role', Consts::ACCOUNT_ROLE_BUYER_BROKER)
            ->whereNull('deleted_at');
        $list = $query->get();
        return $list;
    }


    public static function getAccountIdByManager($accountId)
    {
        // don't belong to any broke net manage role
        $m = self::select(['account_id','net_id','manager'])->where('account_id', $accountId)->where('manager',1)->get();
        if ($m->isEmpty()) {
            $accounts = [['account_id' => $accountId, 'name' => 'my self']];
            return $accounts;
        }
        $netids = $m->map(function ($item,$key){
            return $item->net_id;
        });

        // find all broker net member account id by manager
        $accounts = self::select(['account_id'])->whereIn('net_id', $netids)->get()->toArray();
        $accounts = array_column($accounts, 'account_id');
        $query = self::from('buyer_broker_net_member as m')
            ->select(['a.id as account_id', 'a.name'])
            ->distinct()
            ->join('accounts as a', 'a.id', 'm.account_id')
            ->whereIn('m.account_id', $accounts);
        $list = $query->get();
        return $list;
    }


    public function account()
    {
        return $this->hasOne('App\Models\Account', 'id', 'account_id');
    }
}
