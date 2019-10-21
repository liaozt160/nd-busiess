<?php

namespace App\Http\Controllers\NewDream;

use App\Exceptions\BaseException;
use App\Models\Business;
use App\Models\BusinessAttention;
use App\Models\BusinessBrokerNetMember;
use App\Models\BusinessZh;
use App\Models\Location;
use App\Models\UploadFile;
use App\Traits\Consts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use PDF;
class BusinessController extends BaseController
{

    public function Add(Request $request){
        $param = $request->except('lang');
        $param['business_broker'] = $this->guard()->id();
        $m = Business::addItem($param);
        return $this->ok($m);
    }

    public function Update(Request $request){
        $param = $request->except('id');
        $id = $request->input('id');
        $user = $this->guard()->user();
        Business::accessCheck($id,$user);
        $m = Business::updateItem($id,$param);
        return $this->ok($m);
    }

    public function Delete(Request $request){
        $id = $request->input('id');
        $user = $this->guard()->user();
        Business::accessCheck($id,$user);
        $m = Business::deleteItem($id);
        return $this->ok();
    }

    public function List(Request $request){
        $param = $request->input();
        $user = $this->guard()->user();
        $accountId = $user->role==Consts::ACCOUNT_ROLE_ADMIN?null:$user->id;
        $list = Business::listItem($param,$accountId);
        return $this->ok($list);
    }

    public function Show(Request $request){
        $id = $request->input('id');
        $user = $this->guard()->user();
        Business::accessCheck($id,$user);
        $m = Business::find($id);
        if(!$m){
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
//        $countries = Location::queryChild();
//        if($m->country){
//            $states = Location::queryChild($m->country);
//        }
//        if($m->states){
//            $cities =  Location::queryChild($m->states);
//        }
//        $locations = compact('countries','states','cities');
//        $m->locations = $locations;
        return $this->ok($m);
    }

    /**
     *  query business
     * @param Request $request
     * User: Tank
     * Date: 2019/6/26
     * Time: 9:41
     */
    public function query(Request $request){
        $q = $request->input('q',null);
        $list = Business::getQueryAll($q);
        return $this->ok($list);
    }

    public function queryByAttention(Request $request){
        $q = $request->input('q',null);
        $buyerId = $request->input('buyer_id',null);
        $accountId = $this->guard()->id();
        $list = Business::getQueryByAttention($q,$accountId,$buyerId);
        return $this->ok($list);
    }

    /**
     *  update zh
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws BaseException
     * User: Tank
     * Date: 2019/6/19
     * Time: 10:17
     */
    public function UpdateZh(Request $request){
        $param = $request->except('id');
        $param['business_broker'] = $this->guard()->id();
        $id = $request->input('id',null);
        $user = $this->guard()->user();
        Business::accessCheck($id,$user);
        $m = BusinessZh::updateItem($id,$param);
        return $this->ok($m);
    }

    public function addZh(Request $request){
        $param = $request->except('id');
        $id = $request->input('id',null);
        $user = $this->guard()->user();
        Business::accessCheck($id,$user);
        $m = BusinessZh::updateItem($id,$param);
        return $this->ok($m);
    }

    /**
     *  update zh
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws BaseException
     * User: Tank
     * Date: 2019/6/19
     * Time: 10:17
     */
    public function showZh(Request $request){
        $id = $request->input('id');
        $user = $this->guard()->user();
        Business::accessCheck($id,$user);
        $m = BusinessZh::where('business_id',$id)->first();
        if($m){
            return $this->ok($m);
        }
        return $this->ok(new BusinessZh());
    }

    /**
     * 设置状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws BaseException
     * User: Tank
     * Date: 2019/6/17
     * Time: 13:58
     */
    public function setStatus(Request $request){
        $id = $request->input('id');
        $user = $this->guard()->user();
        Business::accessCheck($id,$user);
        $m = Business::find($id);
        if($m){
            $status = $request->input('status');
            $m->status = $status;
            if(!$m->save()){
                throw new BaseException(Consts::SAVE_RECORD_FAILED);
            }
            return $this->ok($m);
        }
        throw new BaseException(Consts::NO_RECORD_FOUND);
    }

    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * User: Tank
     * Date: 2019/6/17
     * Time: 14:08
     */
    public function attentionList(Request $request){
        $accountId = $this->guard()->id();
        $list = BusinessAttention::getListByBusiness($accountId);
        return $this->ok($list);
    }

    public function attentionDel(Request $request){
        $id = $request->input('id');
        $m = BusinessAttention::delItemByBusiness($id);
        return $this->ok();
    }

    public function BuyerListLevelOne(Request $request){
        $param = $request->post();
        $user = $this->guard()->user();
        $list = Business::getListByBuyerLevelOne($param);
        return $this->ok($list);
    }

    public function BuyerListLevelTwo(Request $request){
        $param = $request->post();
        $accountId = $this->guard()->id();
        $list = Business::getListByBuyerLevelTwo($param,$accountId);
        return $this->ok($list);
    }

    public function showLevelOne(Request $request){
        $accountId = $this->guard()->id();
        $businessId = $request->input('business_id');
        $m = Business::showLevelOne($businessId);
        if($m){
            return $this->ok($m);
        }
        throw new BaseException(Consts::NO_RECORD_FOUND);
    }


    public function showLevelTwo(Request $request){
        $accountId = $this->guard()->id();
        $businessId = $request->input('business_id');
        $m = Business::showLevelTwo($accountId,$businessId);
        if($m){
            return $this->ok($m);
        }
        throw new BaseException(Consts::NO_RECORD_FOUND);
    }

    public function getBusinessBrokers(Request $request){
        $user = $this->guard()->user();
        $accountId = $user->role==Consts::ACCOUNT_ROLE_ADMIN?null:$user->id;
        $list = BusinessBrokerNetMember::getAccountIdByManager($accountId);
        return $this->ok($list);
    }

    public function changeOwner(Request $request){
        $businessId = $request->post('business_id');
        $ownerId = $request->post('owner_id');
        if(!$businessId || !$ownerId){
            throw new BaseException(Consts::PARAM_VALIDATE_WRONG);
        }
        $boolean = Business::changeOwner($businessId,$ownerId);
        return $this->ok();
    }

    /**
     * 生成pdf
     * @param Request $request
     * User: Tank
     * Date: 2019/8/16
     * Time: 10:49
     */
    public function generatePDF(Request $request,$level = '1'){
        $str = $request->input('ids');
        if(!getIdsFromString($str,$ids)){
            throw new BaseException(Consts::PARAM_VALIDATE_WRONG);
        }
        if(!in_array($level,['1','2','3','4'])){
            throw new BaseException(Consts::PARAM_VALIDATE_WRONG);
        }
        $business = Business::getBusinessLevel($ids,(int)$level);
        $user = $this->guard()->user();
        $fileName = $user->id . '/business('.date('Y-m-d').').pdf';
        $pdf = PDF::loadView('pdf.business_level_one',['business' =>$business]);
        $pdf->setOptions(['isPhpEnabled'=> true,'dpi' => 96]);
        $pdf->setPaper('a4');
        $r = UploadFile::saveS3TempPdf($fileName,$pdf->output());
        if($r){
            $url = UploadFile::getS3TempPdf($fileName);
            return $this->ok(['url' => (string)$url]);
        }
        return $this->err(Consts::SAVE_FILE_ERROR);
//        return $pdf->stream($fileName);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws BaseException
     * User: Tank
     * Date: 2019/8/28
     * Time: 16:43
     */
    public function businessPublicStatus(Request $request){
        $id = $request->input('business_id');
        $status = $request->input('public');
        $r = Business::setPublic($id,$status);
        return $this->ok($r);
    }


}
