<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\BaseException;
use App\Traits\Consts;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BuyerBrokerNet extends Model
{
    protected $fillable = [
        'name', 'remark'
    ];
    protected $guarded = ['id'];
    protected $table = 'buyer_broker_net';

    public static function addItem($param, $accountId)
    {
        DB::beginTransaction();
        try {
            $m = new self($param);
            $m->created_by = $accountId;
            if (!$m->save()) {
                throw new BaseException(Consts::DATA_VALIDATE_WRONG);
            }
            if (isset($param['broker_id']) && $param['broker_id']) {
                $m->assignMember($param['broker_id']);
            }
            DB::commit();
            return $m;
        } catch (BaseException $b) {
            throw new BaseException($b->getKey());
        } catch (\Exception $e) {
            throw new BaseException(Consts::SAVE_RECORD_FAILED, $e->getMessage());
        }
    }


    public static function updateItem($id, $param)
    {
        $m = self::find($id);
        if (!$m) {
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        try {
            $m->fill($param);
            if (!$m->save()) {
                throw new BaseException(Consts::DATA_VALIDATE_WRONG);
            }
            if (isset($param['broker_id']) && $param['broker_id']) {
                $m->assignMember($param['broker_id']);
            }
            DB::commit();
            return $m;
        } catch (BaseException $b) {
            throw new BaseException($b->getKey());
        } catch (\Exception $e) {
            throw new BaseException(Consts::SAVE_RECORD_FAILED, $e->getMessage());
        }
    }


    public static function getList($param)
    {
        $query = self::whereNull('deleted_at');
        if (isset($param['q']) && $param['q']) {
            $q = $param['q'];
            $query->where(DB::raw("concat(name,remark)"), 'like', '%' . $q . '%');
        }
        $list = $query->paginate(15);
        return $list;
    }

    public static function delItem($id, $accountId = null)
    {
        $m = self::find($id);
        if (!$m) {
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        if (!$m->nets->isEmpty()) {
            throw new BaseException(Consts::BUYER_BROKER_SUBITEM_NEED_TO_CLEAN_FIRST);
        }
        $m->deleted_at = new Carbon();
        $m->deleted_by = $accountId;
        if ($m->save()) {
            return $m;
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }

    public function assignMember($param)
    {
        if ($param == '[]') {
            BuyerBrokerNetMember::where('net_id', $this->id)->delete();
            return;
        }
        $members = [];
        $exist = BuyerBrokerNetMember::getExist($this->id);
        $exist = array_column($exist->toArray(), 'account_id');
        $param = explode(',', substr($param, 1, strlen($param) - 2));
        $intersect = array_intersect($exist, $param);
        $param = array_diff($param, $intersect);
        foreach ($param as $item) {
            $members[] = ['net_id' => $this->id, 'account_id' => $item];
        }
        BuyerBrokerNetMember::where('net_id', $this->id)->whereNotIn('account_id', $intersect)->delete();
        if ($members) {
            $this->nets()->createMany($members);
        }
    }

    public function withNetBroker(){
        $brokers = BuyerBrokerNetMember::select('account_id')->where('net_id',$this->id)->get()->toArray();
        if($brokers){
            $brokers = array_column($brokers,'account_id');
        }
        $this->broker_id = $brokers;
    }

    public function withFreeNetBroker(){
        $list = BuyerBrokerNetMember::getFreeBuyerBroker(null);
        $this->free_brokers = $list;
    }

    public function nets()
    {
        return $this->hasMany('App\Models\BuyerBrokerNetMember', 'net_id', 'id');
    }

}
