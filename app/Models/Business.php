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

    public static function listItem($param){
        $query  = self::whereNull('deleted_at');
        $list = $query->paginate(15);
        return $list;
    }

    public function accessCheck($user){
        if($user->role == Consts::ACCOUNT_ROLE_USER){
            return true;
        }
        if($user->id == $this->business_broker){
            return true;
        }
        throw new BaseException(Consts::ACCOUNT_ACCESS_DENY);
    }

}