<?php

namespace App\Models;

use App\Exceptions\BaseException;
use App\Traits\Consts;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $guarded = ['id'];
    protected $table = 'business';

    public static function addItem($param)
    {
        $m = new self();
        $m->fill($param);
        if ($m->save()) {
            return $m;
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }

    public static function updateItem($id, $param)
    {
        $m = self::find($id);
        if (!$m) {
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        $m->fill($param);
        if ($m->save()) {
            return $m;
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
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
        $query = self::whereNull('deleted_at');
        if ($accountId) {
            $query->where('business_broker', $accountId);
        }

        if(isset($param['q']) && isset($param['q'])){
            $query->where(DB::raw("concat(company,title)"),'like','%'.$param['q'].'%');
        }

        if(isset($param['price_from']) && $param['price_from']){
            $query->where('price_from', '>=', $param['price_from']);
        }

        if(isset($param['price_to']) && $param['price_to']){
            $query->where('price_to' , '<=', $param['price_to']);
        }

        if(isset($param['status']) && $param['status']){
            $query->where('status' ,$param['status']);
        }
        $list = $query->paginate(15);
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
        if ($user->id == $m->business_broker) {
            return true;
        }
        throw new BaseException(Consts::ACCOUNT_ACCESS_DENY);
    }


    public static function getListByBuyerLevelOne($param)
    {
        $columns = ['id', 'listing', 'title', 'price', 'employee_count', 'status'];
        $query = self::select($columns)->whereNull('deleted_at');
        $list = $query->paginate(15);
        return $list;
    }

    public static function getListByBuyerLevelTwo($param, $accountId)
    {
        $columns = ['b.id', 'b.listing', 'b.title', 'b.price', 'b.employee_count', 'b.status'];
        $query = self::from('business_assign as a')->select($columns)->whereNull('b.deleted_at');
        $query->join('business as b', 'a.business_id', '=', 'b.id')->where('a.account_id', $accountId);
        $list = $query->paginate(15);
        return $list;
    }

    public static function showLevelOne($businessId)
    {
        $columns = ['b.id', 'b.listing', 'b.title', 'b.price', 'b.employee_count','b.profitability'
            ,'b.country','b.states','b.city','b.address','b.real_estate','b.building_sf', 'b.status'];
        $query = self::from('business as b')->select($columns)->whereNull('b.deleted_at')->where('b.id',$businessId);
        $m = $query->first();
        return $m;
    }

    public static function showLevelTwo($accountId, $businessId)
    {
        $columns = ['b.id', 'b.listing', 'b.title', 'b.price', 'b.employee_count','b.profitability'
            ,'b.country','b.states','b.city','b.address','b.real_estate','b.building_sf','b.gross_income',
            'b.value_of_real_estate','b.net_income','b.lease','b.lease_term','b.ebitda','b.ff_e','b.inventory','b.commission','b.buyer_financing', 'b.status'];
        $query = self::from('business_assign as a')->select($columns)->whereNull('b.deleted_at');
        $query->join('business as b', 'a.business_id', '=', 'b.id')
            ->where('a.account_id', $accountId)->where('a.business_id', $businessId);
        $m = $query->first();
        return $m;
    }


    public static function getQueryAll($q=null){
        $query = self::select(['id as key','title as label'])
            ->where('status',Consts::BUSINESS_STATUS_NORMAL)->whereNull('deleted_at')
        ;
        if($q){
            $query->where('title','like','%'.$q.'%');
        }
        return $query->get();
    }



    public function account(){
        return $this->hasOne('App\Models\Account','id','business_broker');
    }


    public function businessZh(){
        return $this->hasOne('App\Models\BusinessZh','business_id','id');
    }

}
