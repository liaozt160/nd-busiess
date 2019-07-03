<?php

namespace App\Models;

use App\Exceptions\BaseException;
use App\Traits\Consts;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BusinessBrokerNet extends Model
{
    protected $fillable = [
        'name', 'remark'
    ];
    protected $guarded = ['id'];
    protected $table = 'business_broker_net';

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
        $m->deleted_at = new Carbon();
        $m->deleted_by = $accountId;
        if ($m->save()) {
            return $m;
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }

    public function assignMember($param)
    {
        $members = [];
        $exist = BusinessBrokerNetMember::getExist($this->id);
        $exist = array_column($exist->toArray(),'account_id');
        $param = explode(',', substr($param, 1, strlen($param) - 2));
        foreach ($param as $item) {
            if (!$item || in_array($item,$exist)) {
                continue;
            }
            $members[] = ['net_id' => $this->id, 'account_id' => $item];
        }
        if($members){
            $this->nets()->createMany($members);
        }
    }

    public function nets()
    {
        return $this->hasMany('App\Models\BusinessBrokerNetMember', 'net_id', 'id');
    }

}
