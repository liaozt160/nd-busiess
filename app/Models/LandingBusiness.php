<?php

namespace App\Models;

use App\Exceptions\BaseException;
use App\Traits\Consts;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\Array_;

class LandingBusiness extends Model
{
    protected $fillable = [
        'name', 'created_by','deleted_at','deleted_by',
    ];
    protected $guarded = ['id'];
    protected $table = 'landing_business';


    public static function addItem($param){
        $m = self::create($param);
        if(!$m){
            throw new BaseException(Consts::SAVE_RECORD_FAILED);
        }
        $businessIds = [];
        if(!(isset($param['business_ids']) && getIdsFromString($param['business_ids'],$businessIds))){
            return $m;
        }
        $details = array_map(function ($v) use ($m){
            return ['landing_business_id'=>$m->id,'business_id'=>$v];
        },$businessIds);
        $m->details()->createMany($details);
        return $m;
    }



    public static function updateItem($param){
        if(!$id = isset($param['id'])?$param['id']:null){
            throw new BaseException(Consts::DATA_VALIDATE_WRONG);
        }
        $m = self::where('id',$id)->update($param);
        return $m;
    }

    public static function deletedItem($id,$accountId)
    {
        $r = self::where('id',$id)->update(['deleted_at' => new Carbon(),'deleted_by'=>$accountId]);
        return $r;
    }


    public static function removeItems($landingBusinessId,$ids = Array()){
        $del = LandingBusinessDetail::whereIn('business_id',$ids)->where('landing_business_id',$landingBusinessId)->delete();
        return $del;
    }

    /**
     * check exists
     * @param $landingBusinessId
     * @param array $ids
     * User: Tank
     * Date: 2019/9/10
     * Time: 11:30
     */
    public static function getDetailExist($landingBusinessId,$ids = Array()){
        $businessExists = LandingBusinessDetail::select(['business_id'])->where('landing_business_id',$landingBusinessId)->whereIn('business_id',$ids)->get()->toArray();
        if(!$businessExists){
            return Array();
        }
        return array_column($businessExists,'business_id');
    }

    /**
     * append details
     * @param array $ids
     * @param null $landingBusinessId
     * @return mixed
     * User: Tank
     * Date: 2019/9/10
     * Time: 11:45
     */
    public static function appendItems($ids = Array(),$landingBusinessId = Null){
        $ids = array_diff($ids,self::getDetailExist($landingBusinessId,$ids));
        $data = array_map(function ($item) use ($landingBusinessId) {
            return $array = ['landing_business_id'=>$landingBusinessId,'business_id' => $item,'created_at' =>new Carbon()];
        },$ids);
        $del = LandingBusinessDetail::insert($data);
        return $del;
    }


    public function details()
    {
        return $this->hasMany('App\Models\LandingBusinessDetail', 'landing_business_id', 'id');
    }

    public static function getDetailBusiness($landingBusinessId){
        $ids = LandingBusinessDetail::select(['business_id'])->where('landing_business_id',$landingBusinessId)->get()->toArray();
        $ids = array_column($ids,'business_id');
        $business = Business::getListByBuyerLevelOne(['ids'=>$ids]);
        return $business;
    }

    public static function getLandingBusiness($landingBusinessId){
        $list = self::getDetailBusiness($landingBusinessId);
        // hide the special attribute
        $list->makeHidden(['location','category_id','country','states','city','company','id','address','immigration_supports']);
        return $list;
    }

    public static function getLandingBusinessLevelOne($param=[]){
        $list = Business::getListByBuyerLevelOne($param);
        // hide the special attribute
//        $list->makeHidden(['location','category_id','country','states','city','company','id','address','immigration_supports']);
        return $list;
    }
}
