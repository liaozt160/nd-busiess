<?php

namespace App\Http\Controllers\NewDream;

use App\Exceptions\BaseException;
use App\Models\LandingBusiness;
use App\Traits\Consts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LandingBusinessController extends BaseController
{


    /**
     * @param Request $request
     * @throws \App\Exceptions\BaseException
     * User: Tank
     * Date: 2019/9/10
     * Time: 10:50
     * Return
     */
    public function Add(Request $request)
    {
        $param = $request->post();
        $param['created_by'] = $this->guard()->id();
        $m = LandingBusiness::addItem($param);
        return $m;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     * User: Tank
     * Date: 2019/9/10
     * Time: 11:09
     */
    public function Update(Request $request)
    {
        $param = $request->post();
        $m = LandingBusiness::updateItem($param);
        return $this->ok($m);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     * User: Tank
     * Date: 2019/9/10
     * Time: 10:51
     */
    public function Delete(Request $request)
    {
        $id = $request->input('id');
        $accountId = $this->guard()->id();
        $r = LandingBusiness::deletedItem($id,$accountId);
        return $this->ok($r);
    }

    public function Remove(Request $request)
    {
        $ids = $request->post('ids');
        $landingBusinessId = $request->post('landing_business_id',1);
        $accountId = $this->guard()->id();
        $r = LandingBusiness::removeItems($landingBusinessId,$ids);
        return $this->ok($r);
    }

    public function Append(Request $request)
    {
        $ids = $request->post('ids');
        $landingBusinessId = $request->post('landing_business_id',1);
        if(!$landingBusinessId || !$ids){
            throw new BaseException(Consts::DATA_VALIDATE_WRONG);
        }
        $r = LandingBusiness::appendItems($ids,$landingBusinessId);
        return $this->ok($r);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * User: Tank
     * Date: 2019/9/10
     * Time: 13:38
     */
    public function Details(Request $request){
        $id = $request->post('id',1);
        $business = LandingBusiness::getDetailBusiness($id);
        return $this->ok($business);
    }


    /**
     * 公开的接口内容
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * User: Tank
     * Date: 2019/9/10
     * Time: 14:54
     */
    public function landingList(Request $request){
        $id = $request->input('id',1);
        $business = LandingBusiness::getLandingBusiness($id);
        return $this->ok($business);
    }

    public function PublicBusinessLevelOne(Request $request){
        $param = $request->input();
        $business = LandingBusiness::getLandingBusinessLevelOne($param);
        return $this->ok($business);
    }


}
