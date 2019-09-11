<?php

namespace App\Models;

use App\Exceptions\BaseException;
use App\Traits\Consts;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Business extends Model
{
    protected $guarded = ['id'];
    protected $table = 'business';

    public static function addItem($param)
    {
        DB::beginTransaction();
        try {
            $m = new self();
            $m->fill($param);
            $save = $m->save();
            if (!$save) {
                throw new BaseException(Consts::SAVE_RECORD_FAILED);
            }
            $bz = $m->businessZh()->create($param);
            DB::commit();
        } catch (BaseException $b) {
            throw new BaseException($b->getKey());
        } catch (\Exception $e) {
            throw new BaseException(Consts::UNKNOWN_ERROR, $e->getMessage());
        }
        return $m;
    }

    public static function updateItem($id, $param)
    {
        $m = self::find($id);
        if (!$m) {
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        $m->fill($param);
        if ($m->save()) {
            $businessZh = $m->businessZh;
            if(!$businessZh){
                throw new BaseException(Consts::SAVE_RECORD_FAILED);
            }
            $businessZh->fillUpdate()->fill($param);
            if($businessZh->save()){
                return $m;
            }
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }


    public static function deleteItem($id)
    {
        $m = self::find($id);
        if (!$m) {
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        $m->deleted_at = new Carbon();
        if ($m->save()) {
            return $m;
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }

    public static function listItem($param, $accountId = null)
    {
        $columns = self::getColumnsByLevel(Consts::ACCOUNT_ACCESS_LEVEL_THREE);
        if (App::getLocale() == 'zh') {
            array_push($columns,'z.business_id as id');
            array_push($columns,'category_zh as category');
            $prifix = 'z.';
        } else {
            array_push($columns,'category_en as category');
            $prifix = 'b.';
        }
//        DB::enableQueryLog();
        array_push($columns,'a.name as account_name');
        $query = self::from('business as b')
            ->select($columns)
            ->leftjoin('business_zh as z', 'b.id', 'z.business_id')
            ->leftjoin('accounts as a', 'b.business_broker', 'a.id')
            ->leftjoin('category as c', $prifix.'category_id', 'c.id')
            ->whereNull('b.deleted_at');
        //with broker id or account's id
        if (isset($param['broker_id']) && $param['broker_id'] != 0) {
            $query->where($prifix . 'business_broker', $param['broker_id']);
        } else {
            if ($accountId) {
                $accountIds = BusinessBrokerNetMember::getAccountIdByManager($accountId);
                if (is_object($accountIds)) {
                    $accountIds = $accountIds->all();
                }
                $accountIds = array_column($accountIds, 'account_id');
                $query->whereIn($prifix . 'business_broker', $accountIds);
            }
        }

        if (isset($param['q']) && isset($param['q'])) {
            $query->where(DB::raw("concat(nd_{$prifix}company,nd_{$prifix}title)"), 'like', '%' . $param['q'] . '%');
        }

        if (isset($param['price_from']) && $param['price_from']) {
            $query->where($prifix . 'price', '>=', $param['price_from']);
        }

        if (isset($param['price_to']) && $param['price_to']) {
            $query->where($prifix . 'price', '<=', $param['price_to']);
        }

        if (isset($param['category_id']) && $param['category_id']) {
            $query->where($prifix . 'category_id', $param['category_id']);
        }

        if (isset($param['state']) && $param['state']) {
            $query->where($prifix . 'states', $param['state']);
        }

        if (isset($param['status']) && $param['status']) {
            $query->where('b.status', $param['status']);
        }

        // order 排序
        $order = (isset($param['order']) && $param['order'] == '1') ? 'ASC' : 'DESC';
        $column = 'b.updated_at';
        if (isset($param['prop']) && $param['prop']) {
            $column = $prifix.$param['prop'];
        }
        $query->orderBy($column, $order);
//        $list = $query->with('account:id,name')->paginate(15);
        $list = $query->paginate(15);
        $list->transform(function ($item, $key){
            $item->setLocations();
            return $item;
        });
//        var_dump(DB::getQueryLog());
        return $list;
    }

    public static function accessCheck($id, $user)
    {
        $m = self::find($id);
        if (!$m) {
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        if ($user->role == Consts::ACCOUNT_ROLE_ADMIN) {
            return true;
        }
        if ($user->id == $m->business_broker) {
            return true;
        }
        $list = BusinessBrokerNetMember::getAccountIdByManager($user->id);
        if (is_object($list)) {
            $list = $list->all();
        }
        $accountIds = array_column($list, 'account_id');
        if (in_array($m->business_broker, $accountIds)) {
            return true;
        }
        throw new BaseException(Consts::ACCOUNT_ACCESS_DENY);
    }


    public static function getListByBuyerLevelOne($param)
    {
        $columns = self::getColumnsByLevel(Consts::ACCOUNT_ACCESS_LEVEL_ONE,true);
        if (App::getLocale() == 'zh') {
            $columnPrefix = 'z.';
            array_push($columns,'category_zh as category');
        } else {
            $columnPrefix = 'b.';
            array_push($columns,'category_en as category');
        }

        $query = self::select($columns)
            ->from('business as b')
            ->leftjoin('business_zh as z', 'b.id', 'z.business_id')
            ->leftjoin('category as c', $columnPrefix.'category_id', 'c.id')
            ->whereNull('b.deleted_at');

        // filter and search
        if (isset($param['q']) && isset($param['q'])) {
            $query->where(DB::raw("concat(nd_{$columnPrefix}company,nd_{$columnPrefix}title)"), 'like', '%' . $param['q'] . '%');
        }

        if (isset($param['price_from']) && $param['price_from']) {
            $query->where($columnPrefix . 'price', '>=', $param['price_from']);
        }

        if (isset($param['price_to']) && $param['price_to']) {
            $query->where($columnPrefix . 'price', '<=', $param['price_to']);
        }

        if (isset($param['state']) && $param['state']) {
            $query->where($columnPrefix . 'states', $param['state']);
        }


        if (isset($param['category_id']) && $param['category_id']) {
            $query->where($columnPrefix . 'category_id', $param['category_id']);
        }

        if (isset($param['status']) && $param['status']) {
            $query->where('b.status', $param['status']);
        }

        //默认未发布内容
        if (isset($param['ids']) && $param['ids']) {
            $query->whereIn('b.id', $param['ids']);
        }else{
            $query->where($columnPrefix . 'public','1');
        }

        // order 排序
        $order = (isset($param['order']) && $param['order'] == '1') ? 'ASC' : 'DESC';
        $column = 'b.updated_at';
        if (isset($param['prop']) && $param['prop']) {
            $column = $param['prop'];
        }
        $query->orderBy($column, $order);
        $list = $query->paginate(15);
        $list->transform(function ($item, $key){
            $item->setLocations();
            return $item;
        });
        return $list;
    }


    public static function getListByBuyerLevelTwo($param, $accountId)
    {
        $columns = self::getColumnsByLevel(Consts::ACCOUNT_ACCESS_LEVEL_ONE,true);
        $query = self::from('business_assign as a')->select($columns)->whereNull('b.deleted_at');
        $query->join('business as b', 'a.business_id', '=', 'b.id')->where('a.account_id', $accountId);
        // order 排序
        $order = (isset($param['order']) && $param['order'] == '1') ? 'ASC' : 'DESC';
        $column = 'b.updated_at';
        if (isset($param['prop']) && $param['prop']) {
            $column = 'b.' . $param['prop'];
        }
        $query->orderBy($column, $order);
        $list = $query->paginate(15);
        $list->transform(function ($item, $key){
            $item->setLocations();
            return $item;
        });
        return $list;
    }

    public static function getColumnsByLevelBack($level = 1,$list = false)
    {
        $levelOneList = ['id', 'listing', 'title', 'company', 'price', 'updated_at', 'created_at','b.status'];
//        $levelOne = ['id', 'listing', 'title', 'price', 'company', 'employee_count', 'profitability'
//            , 'country', 'states', 'city', 'address', 'real_estate', 'building_sf', 'b.status'];
        $levelOne = ['id', 'listing', 'title', 'company', 'price','employee_count','profitability','type'
            , 'country', 'states', 'city', 'address',
//            'real_estate','gross_income','gross_income_unit','net_income', 'net_income_unit','lease','lease_unit', 'building_sf',
            'value_of_real_estate',  'commission','business_description','business_assets', 'b.status','updated_at', 'created_at'];
        $levelTwoList = ['id', 'listing', 'title', 'company', 'price', 'employee_count', 'b.status', 'updated_at', 'created_at'];
        $levelTwo = ['id', 'listing', 'title', 'company', 'price', 'employee_count','profitability','type'
            , 'country', 'states', 'city', 'address', 'real_estate', 'building_sf', 'gross_income','gross_income_unit',
            'franchise','employee_info','value_of_real_estate', 'net_income', 'net_income_unit','lease','lease_unit',  'lease_term', 'ebitda', 'ff_e', 'inventory', 'commission', 'buyer_financing','business_description','business_assets','financial_performance', 'b.status','updated_at', 'created_at'];

//        $levelThreeList = ['id', 'listing', 'title', 'company', 'price', 'employee_count', 'b.status', 'updated_at', 'created_at'];
//        $levelThree = ['id', 'listing', 'title', 'company', 'price', 'employee_count','profitability','type'
//            , 'country', 'states', 'city', 'address', 'real_estate', 'building_sf', 'gross_income','gross_income_unit',
//            'value_of_real_estate', 'net_income', 'net_income_unit','lease','lease_unit',  'lease_term', 'ebitda', 'ff_e', 'inventory', 'commission', 'buyer_financing','business_description','financial_performance','business_assets', 'b.status'];

        $levelThreeList = ['id', 'listing', 'title', 'company', 'price', 'employee_count', 'b.status', 'updated_at', 'created_at'];
        $levelThree = ['id', 'listing', 'title', 'company', 'price', 'employee_count','profitability','type','franchise','employee_info',
            'franchise_reports','tax_returns'
            , 'country', 'states', 'city', 'address', 'real_estate', 'building_sf', 'gross_income','gross_income_unit',
            'value_of_real_estate', 'net_income', 'net_income_unit','lease','lease_unit',  'lease_term', 'ebitda', 'ff_e', 'inventory', 'commission', 'buyer_financing','business_description','business_assets','financial_performance','reason_for_selling', 'b.status','updated_at', 'created_at'];

        $columnPrefix = App::getLocale() == 'zh'? 'z.':'b.';
        if($level == Consts::ACCOUNT_ACCESS_LEVEL_ONE){
            $columns = $list?$levelOneList:$levelOne;
        }elseif($level == Consts::ACCOUNT_ACCESS_LEVEL_TWO){
            $columns = $list?$levelTwoList:$levelTwo;
        }elseif ($level == Consts::ACCOUNT_ACCESS_LEVEL_THREE){
            $columns = $list?$levelThreeList:$levelThree;
        }else{
            $columns = ['*'];
            array_push($columns,'b.status');
        }
        $columns = array_map(function ($item) use ($columnPrefix) {
            if($item == 'id')  return 'b.id';
            if($item == 'b.status') return $item;
            return  $columnPrefix . $item;
        }, $columns);
        return $columns;
    }

    public static function getColumnsByLevel($level = 1,$list = false){

        $levelList = ['id', 'listing', 'title', 'company', 'price', 'updated_at', 'created_at','b.status','country', 'states', 'city', 'address','b.status','category_id','public'];

        $levelOne = ['id', 'listing', 'title', 'company', 'price','employee_count','profitability','type','country', 'states', 'city', 'address','category_id',
            'value_of_real_estate',  'commission','business_description','business_assets', 'b.status','updated_at', 'created_at','b.status','public'];

        $levelTwo = ['real_estate','building_sf','gross_income','gross_income_unit','franchise','employee_info','net_income','net_income_unit','lease','lease_unit','lease_term','ebitda','ff_e','inventory','buyer_financing','financial_performance',
        ];
        $levelThree = [
            'franchise_reports','tax_returns','reason_for_selling'
        ];
        $columnPrefix = App::getLocale() == 'zh'? 'z.':'b.';
        if($level == Consts::ACCOUNT_ACCESS_LEVEL_ONE){
            $columns = $list?$levelList:$levelOne;
        }elseif($level == Consts::ACCOUNT_ACCESS_LEVEL_TWO){
            $columns = $list?$levelList:array_merge($levelOne,$levelTwo);
        }elseif ($level == Consts::ACCOUNT_ACCESS_LEVEL_THREE){
            $columns = $list?$levelList:array_merge($levelOne,$levelTwo,$levelThree);
        }else{
            $columns = ['*'];
//            array_push($columns,'b.status');
        }
        $columns = array_map(function ($item) use ($columnPrefix) {
            if($item == 'id')  return 'b.id';
            if($item == 'b.status') return $item;
            return  $columnPrefix . $item;
        }, $columns);
        return $columns;
    }

    public static function showLevelOne($businessId)
    {
        $columns = self::getColumnsByLevel(Consts::ACCOUNT_ACCESS_LEVEL_ONE);
        $query = self::from('business as b')
            ->leftjoin('business_zh as z', 'b.id', 'z.business_id')
            ->select($columns)->whereNull('b.deleted_at')->where('b.id', $businessId);
        $m = $query->first();
        if ($m) {
            $m->setLocations();
        }
        return $m;
    }

    public static function showLevelTwo($accountId, $businessId)
    {
        $columns = self::getColumnsByLevel(Consts::ACCOUNT_ACCESS_LEVEL_ONE);
        $query = self::from('business_assign as a')->select($columns)->whereNull('b.deleted_at');
        $query->join('business as b', 'a.business_id', '=', 'b.id')
            ->leftjoin('business_zh as z', 'b.id', 'z.business_id')
            ->where('a.account_id', $accountId)->where('a.business_id', $businessId);
        $m = $query->first();
        if ($m) {
            $m->setLocations();
        }
        return $m;
    }


    public static function getQueryAll($q = null)
    {
        $query = self::select(['id as key', 'title as label'])
            ->where('status', Consts::BUSINESS_STATUS_NORMAL)->whereNull('deleted_at');
        if ($q) {
            $query->where('title', 'like', '%' . $q . '%');
        }
        return $query->get();
    }

    public static function getQueryByAttention($q = null, $accountId = null, $buyer = null)
    {
//        DB::enableQueryLog();
        $query = self::select(['b.id as key', 'b.title as label'])
            ->from('attention_to_business as a')
            ->join('business as b', 'a.business_id', '=', 'b.id')
            ->where('status', Consts::BUSINESS_STATUS_NORMAL)->whereNull('deleted_at');
        if ($accountId) {
            $query->where('account_id', $accountId);
        }
        if ($buyer) {
            $query->where('buyer_id', $buyer);
        }
        if ($q) {
            $query->where('b.title', 'like', '%' . $q . '%');
        }
        $list = $query->get();
//        var_dump(DB::getQueryLog());
        return $list;
    }


    public function account()
    {
        return $this->hasOne('App\Models\Account', 'id', 'business_broker');
    }


    public function businessZh()
    {
        return $this->hasOne('App\Models\BusinessZh', 'business_id', 'id');
    }

    public function category()
    {
        return $this->hasOne('App\Models\BusinessCategory', 'id', 'category_id');
    }

    public function setLocation($lang = 'en')
    {
        $column = $lang == 'en' ? 'MergerNameEn as merger' : 'MergerName as merger';
        $columnName = $lang == 'en' ? 'NameEn as name' : 'Name as name';
        $code = $this->country ? $this->country : null;
        $code = $this->states ? $this->states : $code;
        $code = $this->city ? $this->city : $code;
        $location = Location::select([$column, $columnName])->where('code', $code)->first();
        $this->location = $location ? implode(',', $location->toArray()) : '';
    }


    public static function businessSum($accountId = null, $status = null)
    {
        $query = self::whereNull('deleted_at');
        if ($accountId) {
            $query->where('business_broker', $accountId);
        }
        if ($status) {
            $query->where('status', $status);
        }
        return $query->count();
    }

    public static function changeOwner($businessId, $ownerId)
    {
        DB::beginTransaction();
        try{
            $m = self::where(['id' => $businessId])->update(['business_broker' => $ownerId]);
            $m = BusinessZh::where(['business_id' => $businessId])->update(['business_broker' => $ownerId]);
            DB::commit();
        }catch (BaseException $b){
            throw new BaseException($b->getKey());
        }catch (\Exception $e){
            throw new BaseException(Consts::UNKNOWN_ERROR,$e->getMessage());
        }
        return $m;
    }

    public static function getBusinessLevel($ids,$level = Consts::ACCOUNT_ACCESS_LEVEL_ONE){
        $columns = Business::getColumnsByLevel($level);
        if (App::getLocale() == 'zh') {
            $columnPrefix = 'z.';
        } else {
            $columnPrefix = 'b.';
        }
        $query = self::select($columns)
            ->from('business as b')
            ->leftjoin('business_zh as z', 'b.id', 'z.business_id')
            ->whereIn('b.id',$ids);
//            ->whereNull('b.deleted_at');
        $list = $query->get();
        $list->transform(function ($item, $key){
            $item->setLocations();
            return $item;
        });
        return $list;
    }

    public function setLocations(){
        $lang = App::getLocale();
        if($this->city){
            $code = $this->city;
        }elseif ($this->states){
            $code = $this->states;
        }else{
            $code = $this->country;
        }
        $location = Location::getLocationByCode($code,$lang);
        if($location){
            $this->location=$location->location .' '. $this->address;
        }
        return $this;
    }

    public function fillUpdate()
    {
        $this->fillable(BusinessZh::getFillableParam());
        return $this;
    }


    public static function setPublic($businessId,$status=0){
        try{
            self::where('id',$businessId)->update(['public'=>$status]);
            BusinessZh::where('business_id',$businessId)->update(['public'=>$status]);
        }catch (\Exception $e){
            throw new BaseException(Consts::SAVE_RECORD_FAILED,$e->getMessage());
        }
        return true;
    }

}
