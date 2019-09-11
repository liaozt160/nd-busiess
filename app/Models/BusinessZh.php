<?php

namespace App\Models;

use App\Exceptions\BaseException;
use App\Traits\Consts;
use Illuminate\Database\Eloquent\Model;

class BusinessZh extends Model
{
    protected $guarded = ['business_id'];
    protected $table = 'business_zh';

    public static function updateItem($businessId,$param){
        $m = self::where('business_id',$businessId)->first();
        if(!$m){
            $m = new self();
            $m->business_id = $businessId;
        }
        $m->fill($param);
        if($m->save()){
            $business = $m->business;
            if(!$business){
                throw new BaseException(Consts::SAVE_RECORD_FAILED);
            }
            $business->fillUpdate()->fill($param);
            if($business->save()){
                return $m;
            }
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }


    public static function addItem($param)
    {
        $business = Business::addItem([]);
        if(!$business){
            throw new BaseException(Consts::SAVE_RECORD_FAILED);
        }
        $m = new self();
        $m->business_id = $business->id;
        $m->fill($param);
        if ($m->save()) {
            $m->id = $m->business_id;
            return $m;
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }

    public function business()
    {
        return $this->hasOne('App\Models\Business', 'id', 'business_id');
    }

    public function fillUpdate()
    {
        $this->fillable(self::getFillableParam());
        return $this;
    }

    public static function getFillableParam(){
        $fillable = [
            'listing','price','employee_count','country','states','city',
            'profitability','real_estate','gross_income','value_of_real_estate',
            'net_income','lease','lease_term','ebitda','ff_e','inventory','commission',
            'building_sf','status','franchise','category_id','public'
        ];
        return $fillable;
    }
}
