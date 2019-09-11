<?php

namespace App\Models;

use App\Exceptions\BaseException;
use App\Traits\Consts;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class RecommendToBuyerBroker extends Model
{
    protected $fillable = [
        'name', 'created_by','deleted_at','deleted_by','broker_id','buyer_id'
    ];
    protected $appends = ['broker_name','buyer_name'];
    protected $guarded = ['id'];
    protected $hidden = ['broker','buyer'];
    protected $table = 'recommend_to_buyer_broker';

    public static function list($q, $accountId=null){
        $query = self::select('*')->whereNull('deleted_at');
        if($q){
            $query->where('name','like',"%{$q}%");
        }
        $ids = BuyerBrokerNetMember::getAccountIdByManager($accountId);
        if(is_object($ids)){
            $ids = $ids->all();
            $ids = array_column($ids,'account_id');
            $query->whereIn('broker_id',$ids);
        }
        $list = $query->with('broker:id,name','buyer:id,buyer')->paginate(15);
        return $list;
    }

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
            return ['recommend_id'=>$m->id,'business_id'=>$v];
        },$businessIds);
        $m->details()->createMany($details);
        return $m;
    }

    /**
     * @param $param
     * @return mixed
     * @throws BaseException
     * User: Tank
     * Date: 2019/9/11
     * Time: 13:41
     */
    public static function updateItem($param){
        if(!$id = isset($param['id'])?$param['id']:null){
            throw new BaseException(Consts::DATA_VALIDATE_WRONG);
        }
        $m = self::where('id',$id)->update($param);
        return $m;

    }


    /**
     * @param $id
     * @param $accountId
     * @return mixed
     * User: Tank
     * Date: 2019/8/26
     * Time: 16:06
     */
    public static function deletedItem($id,$accountId)
    {
        $r = self::where('id',$id)->update(['deleted_at' => new Carbon(),'deleted_by'=>$accountId]);
        return $r;
    }


    /**
     * @param array $ids
     * @param null $accountId
     * @return mixed
     * User: Tank
     * Date: 2019/8/28
     * Time: 9:33
     */
    public static function removeItems($recommendId,$ids = Array()){
        $del = RecommendToBuyerBrokerDetail::whereIn('business_id',$ids)->where('recommend_id',$recommendId)->delete();
        return $del;
    }


    /**
     * @param array $ids
     * @param null $recommendId
     * @return mixed
     * User: Tank
     * Date: 2019/8/28
     * Time: 9:43
     */
    public static function appendItems($ids = Array(),$recommendId = Null){
        $data = array_map(function ($item) use ($recommendId) {
                return $array = ['recommend_id'=>$recommendId,'business_id' => $item,'created_at' =>new Carbon()];
        },$ids);
        $del = RecommendToBuyerBrokerDetail::insert($data);
        return $del;
    }


    public function details()
    {
        return $this->hasMany('App\Models\RecommendToBuyerBrokerDetail', 'recommend_id', 'id');
    }

    public function broker()
    {
        return $this->hasOne('App\Models\Account', 'id', 'broker_id');
    }

    public function buyer()
    {
        return $this->hasOne('App\Models\Buyer', 'id', 'buyer_id');
    }

    public function getBrokerNameAttribute()
    {
        $broker = $this->broker;
        return $this->attributes['broker_name'] = $broker?$broker->name:'';
    }

    public function getBuyerNameAttribute()
    {
        $buyer = $this->buyer;
        return $this->attributes['buyer_name'] = $buyer?$buyer->buyer:'';
    }


    public static function getDetailBusiness($recommendId){
        $ids = RecommendToBuyerBrokerDetail::select('business_id')->where('recommend_id',$recommendId)->get()->toArray();
        $ids = array_column($ids,'business_id');
        $business = Business::getListByBuyerLevelOne(['ids'=>$ids]);
        return $business;
    }

    public static function getRecommendQuery(){
        $list = self::select(['id','name'])->whereNull('deleted_at')->get()->toArray();
        $list = array_map(function ($item) {
           unset($item['broker_name']);
           unset($item['buyer_name']);
           return $item;
        },$list);
        return $list;
    }



}
