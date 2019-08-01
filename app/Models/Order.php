<?php

namespace App\Models;

use App\Exceptions\BaseException;
use App\Traits\Consts;
use Carbon\Carbon;
use function foo\func;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $guarded = ['id'];
    protected $fillable = [
        'order_no', 'paid', 'pay_amount', 'status', 'remark', 'buyer_id'
    ];
    protected $table = 'buyer_order';

    public static function addItem($param, $accountId)
    {
        if (isset($param['paid']) && $param['paid'] == Consts::ORDER_PAYMENT_INSPECT) {
            $param['status'] = Consts::ORDER_STATUS_INSPECT_UNPAID;
        }
        DB::beginTransaction();
        try {
            $m = new self();
            $m->fill($param);
            $m->order_no = generateOrderNo($accountId);
            $m->account_id = $accountId;
            if (!$m->save()) {
                throw new BaseException(Consts::SAVE_RECORD_FAILED);
            }
            if (!isset($param['business_ids'])) {
                return $m;
            }
            $business = $param['business_ids'];
            $business = explode(',', substr($business, 1, strlen($business) - 2));
            if (sizeof($business) > 10) {
                DB::rollBack();
                throw new BaseException(Consts::PARAM_VALIDATE_OUT_OF_LIMIT);
            }
            foreach ($business as $item) {
                $details[] = ['order_no' => $m->order_no, 'business_id' => $item];
            }
            $m->orderDetail()->createMany($details);
            $m->payInfo()->create(['order_id' => $m->id, 'payment' => $m->paid]);
            DB::commit();
            return $m;
        } catch (BaseException $b) {
            throw new BaseException($b->getKey());
        } catch (\Exception $e) {
            DB::rollBack();
            throw new BaseException(Consts::SAVE_RECORD_FAILED);
        }
    }

    public static function updateItem($id, $param)
    {
        $m = self::find($id);
        if (!$m) {
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        DB::beginTransaction();
        try {
            $m->fill($param);
            if (!$m->save()) {
                throw new BaseException(Consts::SAVE_RECORD_FAILED);
            }
            if (!isset($param['business_ids'])) {
                return $m;
            }
            $m->orderDetail()->delete();
            $business = $param['business_ids'];
            $business = explode(',', substr($business, 1, strlen($business) - 2));
            if (sizeof($business) > 10) {
                DB::rollBack();
                throw new BaseException(Consts::PARAM_VALIDATE_OUT_OF_LIMIT);
            }
            foreach ($business as $item) {
                $details[] = ['order_no' => $m->order_no, 'business_id' => $item];
            }
            $m->orderDetail()->createMany($details);
            DB::commit();
            return $m;
        } catch (BaseException $b) {
            throw new BaseException($b->getKey());
        } catch (\Exception $e) {
            DB::rollBack();
            throw new BaseException(Consts::SAVE_RECORD_FAILED);
        }
        return $m;
    }


    public static function deleteItem($id)
    {
        $m = self::find($id);
        if (!$m) {
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        $m->deleted_at = new Carbon();
        if ($m->save()) {
            return $m;
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }

    public static function listItem($param, $buyerId = null, $accountId = null)
    {
//        DB::enableQueryLog();
        $column = ['o.id', 'o.account_id', 'b.name as account_name', 'b.role as account_role', 'o.buyer_id', 'c.buyer as buyer_name', 'o.audit_id', 'a.name as audit_name', 'o.order_no', 'o.paid', 'o.pay_amount', 'o.remark', 'o.created_at', 'o.audit_at', 'o.status'];
        $query = self::from('buyer_order as o')
            ->select($column)
            ->leftjoin('accounts as a', 'o.audit_id', '=', 'a.id')
            ->leftjoin('accounts as b', 'o.account_id', '=', 'b.id')
            ->leftjoin('buyer as c', 'o.buyer_id', '=', 'c.id')
            ->whereNull('o.deleted_at');
        if ($buyerId) {
            $query->where('o.buyer_id', $buyerId);
        } else {
            if ($accountId) {
                $query->where('o.account_id', $accountId);
            } else {
                $query->whereNotIn('o.status',[Consts::ORDER_STATUS_INSPECT_UNPAID,Consts::ORDER_STATUS_INFO_UNPAID]);
            }
        }
        $list = $query->paginate(15);
//        var_dump(DB::getQueryLog());
        return $list;
    }

    public static function accessCheck($id, $user)
    {
        $m = self::find($id);
        if (!$m) {
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        if ($user->role == Consts::ACCOUNT_ROLE_ADMIN) {
            return true;
        }
        if ($user->id == $m->account_id) {
            return true;
        }
        throw new BaseException(Consts::ACCOUNT_ACCESS_DENY);
    }

    public function orderDetail()
    {
        return $this->hasMany('App\Models\OrderDetail', 'order_id', 'id');
    }

    public function payInfo()
    {
        return $this->hasMany('App\Models\OrderPayInfo', 'order_id', 'id');
    }

    public function buyer()
    {
        return $this->hasOne('App\Models\Buyer', 'id', 'buyer_id');
    }

    public function audit()
    {
        return $this->hasOne('App\Models\Account', 'id', 'audit_id');
    }

    public static function getItemByBuyerId($id)
    {
        $query = self::where('id', $id);
        $query->with('buyer:id,buyer', 'audit:id,name');
//        $query->with(['orderDetail' =>function($query){
//            $query->select(['id','order_id','business_id']);
//        }]);
        $m = $query->first();
        if (!$m) {
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        $details = OrderDetail::getBusinessWithId($m->id)->toArray();
        if ($details) {
            $details = array_column($details, 'business_id');
        }
        $m->order_detail = $details;
        return $m;
    }

    public static function auditItem($id, $status, $auditId)
    {
//        if (!($status == 2 || $status == 3)) {
//            throw new BaseException(Consts::STATUS_OUT_OF_RANGE);
//        }
        $m = self::find($id);
        if (!$m) {
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }

        $m->audit_id = $auditId;
        $m->audit_at = new Carbon();
        $m->status = $status;
        if (!$m->save()) {
            throw new BaseException(Consts::SAVE_RECORD_FAILED);
        }
        if ($status == 2) {
            $pay = $m->payInfo()->where('payment', 1)->first();
            if ($pay) {
                $pay->verification = 1;
                $pay->save();
            }
        }
        if ($status == 6) {
            $pay = $m->payInfo()->where('payment', 2)->first();
            if ($pay) {
                $pay->verification = 1;
                $pay->save();
            }
        }
        return $m;
    }

    public static function updateStatus($id, $status)
    {
//        if (!($status == 0 || $status == 1)) {
//            throw new BaseException(Consts::STATUS_OUT_OF_RANGE);
//        }
        $m = self::find($id);
        if (!$m) {
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        $m->status = $status;
        if (!$m->save()) {
            throw new BaseException(Consts::SAVE_RECORD_FAILED);
        }
        if ($status == 2) {
            $pay = $m->payInfo()->where('payment', 1)->first();
            if ($pay) {
                $pay->verification = 1;
                $pay->save();
            }
        }
        if ($status == 6) {
            $pay = $m->payInfo()->where('payment', 2)->first();
            if ($pay) {
                $pay->verification = 1;
                $pay->save();
            }
        }
        return $m;
    }

    public static function getDetailList($id, $level = Consts::ACCOUNT_ACCESS_LEVEL_ONE, $isAdmin = false)
    {
        $m = self::with('buyer:id,buyer', 'audit:id,name')->find($id);
        if (!$m) {
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        if ($isAdmin) {   //admin access watch all
            $level = Consts::ACCOUNT_ACCESS_LEVEL_FOUR;
        } else {
            $pays = $m->payInfo()->whereNotNull('verification')->get();
            if ($pays->isEmpty()) {
                $level = Consts::ACCOUNT_ACCESS_LEVEL_ONE;
            } elseif ($pays->count() > 1) {
                $level = Consts::ACCOUNT_ACCESS_LEVEL_THREE; //decide the level from the pay detail
            } else {
                $pay = $pays->pop();
                if ($pay->payment == Consts::ORDER_PAYMENT_INSPECT) {
                    $level = Consts::ACCOUNT_ACCESS_LEVEL_THREE;
                } else {
                    $level = Consts::ACCOUNT_ACCESS_LEVEL_TWO;
                }
            }
        }
        $m->details = OrderDetail::getBusinessLevel($m->id, $level);
        return $m;
    }
}