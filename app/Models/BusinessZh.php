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

}
