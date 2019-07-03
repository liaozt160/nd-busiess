<?php

namespace App\Models;

use App\Exceptions\BaseException;
use App\Traits\Consts;
use Illuminate\Database\Eloquent\Model;

class BusinessBrokerNetMember extends Model
{
    protected $fillable = [
       'net_id','account_id'
    ];
    protected $guarded = ['id'];
    protected $table = 'business_broker_net_member';


    public static function getExist($netId){
        $list = self::select(['account_id'])->where('net_id',$netId)->get();
        return $list;
    }

    public static function getMember($netId){
        $list = self::where('net_id',$netId)->with(['account:id,name'])->get();
        return $list;
    }

    public static function setManager($id,$status = 0){
        $update = self::where(['id' => $id])->update(['manager' => $status]);
        if($update){
            return $update;
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }

    public function account(){
        return $this->hasOne('App\Models\Account','id','account_id');
    }
}
