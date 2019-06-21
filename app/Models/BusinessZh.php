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
            return $m;
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

}
