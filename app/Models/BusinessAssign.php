<?php

namespace App\Models;

use App\Traits\Consts;
use Illuminate\Database\Eloquent\Model;

class BusinessAssign extends Model
{
    protected $table = 'business_assign';
    protected $guarded = ['id'];
    public static function addItems($accountId,$business=[]){
        $del = self::where('account_id',$accountId)->delete();
        $business = array_unique($business);
        foreach ($business as $item){
            if(!$item){
                continue;
            }
            self::create(['account_id'=>$accountId,'business_id' => $item]);
        }
        $list = self::getAssignList($accountId);
        return $list;
    }

    public static function getAssignList($accountId){
        $query = self::from('business_assign as b')
                ->select(['a.name','b.account_id','b.business_id','c.title','b.created_at'])
                ->leftjoin('accounts as a','b.account_id','=','a.id')
                ->leftjoin('business as c','b.business_id','=','c.id')
        ;
        $query->where('b.account_id',$accountId);
        $list = $query->paginate(15);
        return $list;
    }

    public static function getAssignListTo($accountId){
        $list = self::select('business_id')->where('account_id',$accountId)->get();
        return $list;
    }

}
