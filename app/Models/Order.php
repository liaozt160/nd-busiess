<?php

namespace App\Models;

use App\Exceptions\BaseException;
use App\Traits\Consts;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $guarded = ['id'];
    protected $fillable = [
        'account_id', 'order_no', 'paid', 'pay_amount', 'status', 'remark','buyer_id'
    ];
    protected $table = 'buyer_order';

    public static function addItem($param,$accountId)
    {
        DB::beginTransaction();
        try{
        $m = new self();
        $m->fill($param);
        $m->order_no = generateOrderNo($accountId);
        $m->account_id = $accountId;
        if (!$m->save()) {
            throw new BaseException(Consts::SAVE_RECORD_FAILED);
        }
        if(!isset($param['business_ids'])){
            return $m;
        }
        $business = $param['business_ids'];
        $business = explode(',',substr($business,1,strlen($business)-2));
        foreach ($business as $item){
            $details[] = ['order_no'=>$m->order_no,'business_id'=>$item];
        }
        $m->orderDetail()->createMany($details);
        DB::commit();
        return $m;
        }catch (\Exception $e){
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
        try{
            $m->fill($param);
            if (!$m->save()) {
                throw new BaseException(Consts::SAVE_RECORD_FAILED);
            }
            if(!isset($param['business_ids'])){
                return $m;
            }
            $m->orderDetail()->delete();
                     $business = $param['business_ids'];
            $business = explode(',',substr($business,1,strlen($business)-2));
            foreach ($business as $item){
                $details[] = ['order_no'=>$m->order_no,'business_id'=>$item];
            }
            $m->orderDetail()->createMany($details);
            DB::commit();
            return $m;
        }catch (\Exception $e){
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

    public static function listItem($param, $accountId = null)
    {
        $column = ['o.account_id','b.name','o.order_no','o.paid','o.pay_amount','o.remark','o.created_at','o.audit_at','o.status'];
        $query = self::from('buyer_order as o')
            ->select($column)
            ->leftjoin('accounts as a','o.audit_id','=','a.id')
            ->leftjoin('accounts as b','o.account_id','=','b.id')
            ->whereNull('o.deleted_at');
        if($accountId){
            $query->where('o.account_id',$accountId);
        }
        $list = $query->paginate(15);
        return $list;
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
        throw new BaseException(Consts::ACCOUNT_ACCESS_DENY);
    }

    public function orderDetail(){
        return $this->hasMany('App\Models\OrderDetail','order_id','id');
    }
}
