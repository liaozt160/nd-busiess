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
        'order_no', 'paid', 'pay_amount', 'status', 'remark','buyer_id'
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

    public static function listItem($param,$buyerId = null, $accountId = null)
    {
        $column = ['o.id','o.account_id','b.name as account_name','b.role as account_role','o.buyer_id','c.buyer as buyer_name','o.audit_id','a.name as audit_name','o.order_no','o.paid','o.pay_amount','o.remark','o.created_at','o.audit_at','o.status'];
        $query = self::from('buyer_order as o')
            ->select($column)
            ->leftjoin('accounts as a','o.audit_id','=','a.id')
            ->leftjoin('accounts as b','o.account_id','=','b.id')
            ->leftjoin('buyer as c','o.buyer_id','=','c.id')
            ->whereNull('o.deleted_at');
        if($buyerId){
            $query->where('o.buyer_id',$buyerId);
        }
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

    public function buyer(){
        return $this->hasOne('App\Models\Buyer','id','buyer_id');
    }

    public function audit(){
        return $this->hasOne('App\Models\Account','id','audit_id');
    }

    public static function getItemByBuyerId($id){
        $query = self::where('id',$id);
        $query->with('buyer:id,buyer','audit:id,name');
//        $query->with(['orderDetail' =>function($query){
//            $query->select(['id','order_id','business_id']);
//        }]);
        $m = $query->first();
        if(!$m){
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        $details = OrderDetail::getBusinessWithId($m->id)->toArray();
        if($details){
            $details= array_column($details,'business_id');
        }
        $m->order_detail = $details;
        return $m;
    }

    public static function auditItem($id,$auditId){
        $m = self::find($id);
        if(!$m){
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        if($m->audit_id){
            throw new BaseException(Consts::RECORD_EXIST);
        }
        $m->audit_id = $auditId;
        $m->audit_at = new Carbon();
        $m->status = Consts::ORDER_STATUS_PENDING;
        if($m->save()){
            return $m;
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }

    public static function updateStatus($id,$status){
        if(!($status == 0 || $status == 1)){
            throw new BaseException(Consts::STATUS_OUT_OF_RANGE);
        }
        $m = self::find($id);
        if(!$m){
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        $m->status = $status;
        if(!$m->save()){
            throw new BaseException(Consts::SAVE_RECORD_FAILED);
        }
        return $m;
    }

    public static function getDetailList($id){
        $m = self::with('buyer:id,buyer','audit:id,name')->find($id);
        if(!$m){
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        $m->details = OrderDetail::getBusinessLevelTwo($m->id);
        return $m;
    }

}
