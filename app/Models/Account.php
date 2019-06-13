<?php

namespace App\Models;

use App\Exceptions\BaseException;
use App\Traits\ApiTrait;
use App\Traits\Consts;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Account extends Authenticatable implements JWTSubject
{
    protected $fillable = [
        'email', 'phone', 'name', 'is_agent', 'access_level', 'role', 'password',
    ];
    protected $appends = ['roles'];

    protected $hidden = ['password','remember_token'];

    public static function addAccount($param = [])
    {
        $m = self::where('email', $param['email'])->whereNull('deleted_at')->first();
        if($m){
            throw new BaseException(Consts::ACCOUNT_EXIST);
        }
        try {
            if(isset($param['password']) && $param['password']){
                $param['password'] = password_hash($param['password'],PASSWORD_BCRYPT);
            }
            $m = self::create($param);
            if ($m) {
                return $m;
            }
        } catch (\Exception $e) {
            throw new BaseException($e->getMessage());
        }
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }


    public function getJWTCustomClaims()
    {
        return [];
    }


    public static function accountDelete($id){
        $m = self::find($id);
        if(!$m){
            return $m;
        }
        $m->deleted_at = new Carbon();
        if($m->save()){
            return $m;
        }
        return false;
    }

    public static function accountUpdate($id,$param=[]){
        $m = self::find($id);
        if(!$m){
            return $m;
        }
        unset($param['password']);
        $m->fill($param);
        if($m->save()){
            return $m;
        }
        return false;
    }

    public static function getList(){
        $query  = self::whereNull('deleted_at');
        $list = $query->paginate(15);
        return $list;
    }

    /**
     * User: Tank
     * Date: 2019/6/11
     * Time: 16:48
     */
    public static function loginByToken($param=[]){

    }

    public function getRolesAttribute()
    {
        $roles = [];
        if($this->role == 1){
            $roles  = ['admin'];
        }else if($this->role == 2){
            $roles  = ['buyer_broker'];
        }else if($this->role == 3){
            $roles  = ['business_broker'];
        }else{
            $roles  = [];
        }
        return $this->attributes['roles'] = $roles;
    }


}

