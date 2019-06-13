<?php

namespace App\Models;

use App\Exceptions\BaseException;
use App\Traits\Consts;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    protected $guarded = ['id'];
    protected $table = 'buyer';
    public static function addItem($param){
        $m = new self();
        $m->fill($param);
        if($m->save()){
            return $m;
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }

    public static function updateItem($id,$param){
        $m = self::find($id);
        if(!$m){
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        $m->fill($param);
        if($m->save()){
            return $m;
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }


    public static function deleteItem($id){
        $m = self::find($id);
        if(!$m){
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        $m->deleted_at = new Carbon();
        if($m->save()){
            return $m;
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }

    public static function listItem($param,$accountId=null){
        $query  = self::whereNull('deleted_at');
        if($accountId){
            $query->where('buyer_broker',$accountId);
        }
        $list = $query->paginate(15);
        return $list;
    }

    public static function accessCheck($id,$user){
        $m = self::find($id);
        if(!$m){
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        if($user->role == Consts::ACCOUNT_ROLE_USER){
            return true;
        }
        if($user->id == $m->buyer_broker){
            return true;
        }
        throw new BaseException(Consts::ACCOUNT_ACCESS_DENY);
    }
}
