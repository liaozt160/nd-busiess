<?php

namespace App\Models;

use App\Exceptions\BaseException;
use App\Traits\ApiTrait;
use App\Traits\Consts;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Account extends Authenticatable implements JWTSubject
{
    protected $fillable = [
        'email', 'phone', 'name', 'is_agent', 'access_level', 'role', 'password','status','remarks'
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
        if(isset($param['password']) && $param['password']){
            $param['password'] = password_hash($param['password'],PASSWORD_BCRYPT);
        }
        $m->fill($param);
        if($m->save()){
            return $m;
        }
        return false;
    }

    public static function getList($param=array()){
        $query  = self::whereNull('deleted_at');
        if(isset($param['q']) && $param['q']){
            $query->where(DB::raw("concat(email,phone,name)"),'like','%'.$param['q'].'%');
        }
        if(isset($param['role']) && $param['role']){
            $query->where('role',$param['role']);
        }
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

    /**
     * User: Tank
     * Date: 2019/6/13
     * Time: 19:47
     */
    public static function updateStatus($id,$status){
        $m = self::find($id);
        if(!$m){
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        $m->status = $status;
        if($m->save()){
            return $m;
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }

    public static function passwordUpdate($accountId,$newPassword,$oldPassword=null){
        $m = self::find($accountId);
        if(!$m){
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        if($oldPassword && !password_verify($oldPassword,$m->password)){
            throw new BaseException(Consts::PASSWORD_VERIFY_FAILED);
        }
        $m->password = password_hash($newPassword,PASSWORD_BCRYPT);
        if($m->save()){
            return $m;
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }

    public static function getSumByBuyerBroker(){
        $count = self::whereNull('deleted_at')->where('role',Consts::ACCOUNT_ROLE_BUYER_BROKER)->count();
        return $count;
    }

    public static function getSumByBusinessBroker(){
        $count = self::whereNull('deleted_at')->where('role',Consts::ACCOUNT_ROLE_BUSINESS_BROKER)->count();
        return $count;
    }

    public static function getBusinessBrokers(){
        $column=['id as key','name as label'];
        $condition=['role'=>3,'status' =>1];
        $list = self::select($column)->where($condition)->get();
        return $list;
    }



}

