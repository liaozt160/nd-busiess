<?php

namespace App\Http\Controllers\NewDream;

use App\Exceptions\BaseException;
use App\Models\Business;
use App\Models\BusinessAttention;
use App\Models\Buyer;
use App\Traits\Consts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;

class BuyerController extends BaseController
{
    public function Add(Request $request){
        $param = $request->post();
        $param['buyer_broker'] = $this->guard()->id();
        $m = Buyer::addItem($param);
        return $this->ok($m);
    }

    public function Update(Request $request){
        $param = $request->except('id');
        $id = $request->input('id');
        $user = $this->guard()->user();
        Buyer::accessCheck($id,$user);
        $m = Buyer::updateItem($id,$param);
        return $this->ok($m);
    }

    public function Delete(Request $request){
        $id = $request->input('id');
        $user = $this->guard()->user();
        Buyer::accessCheck($id,$user);
        $m = Buyer::deleteItem($id);
        return $this->ok();
    }

    public function List(Request $request){
        $param = $request->input();
        $user = $this->guard()->user();
        $accountId = $user->role==Consts::ACCOUNT_ROLE_ADMIN?null:$user->id;
        $list = Buyer::listItem($param,$accountId);
        return $this->ok($list);
    }

    public function Show(Request $request){
        $id = $request->input('id');
        $user = $this->guard()->user();
        Buyer::accessCheck($id,$user);
        $m = Buyer::find($id);
        if($m){
            return $this->ok($m);
        }
        throw new BaseException(Consts::NO_RECORD_FOUND);
    }

    public function attentionPay(Request $request){
        $param = $request->only(['business_id','account_id','buyer_id']);
        $param['account_id'] = $this->guard()->id();
        $m = BusinessAttention::addItemByArray($param);
        return $this->ok($m);
    }

    public function attentionList(Request $request){
        $accountId = $this->guard()->id();
        $param = $request->input();
        $list = BusinessAttention::getListByBuyer($accountId,$param);
        return $this->ok($list);
    }

    public function attentionDel(Request $request){
        $str = $request->input('ids');
        if(!getIdsFromString($str,$ids)){
            throw new BaseException(Consts::PARAM_VALIDATE_WRONG);
        }
        $m = BusinessAttention::delItemByBuyer($ids);
        return $this->ok();
    }

    public function attentionPdf(Request $request){
        $str = $request->input('ids');
        if(!getIdsFromString($str,$ids)){
            throw new BaseException(Consts::PARAM_VALIDATE_WRONG);
        }
        $business = Business::getBusinessLevel($ids,Consts::ACCOUNT_ACCESS_LEVEL_ONE);
//        return view('pdf.business_level_one',['business' =>$business]);
        $fileName = 'business('.date('Y-m-d').').pdf';
        $pdf = PDF::loadView('pdf.business_level_one',['business' =>$business]);
        $pdf->setOptions(['isPhpEnabled'=> true,'dpi' => 96]);
        $pdf->setPaper('a4');
        return $pdf->stream($fileName);
    }


    public function Query(Request $request){
//        $accountId = $this->guard()->id();
        $accountId = $request->input('id');
        if(!$accountId){
            $user = $this->guard()->user();
            $accountId = $user->role==Consts::ACCOUNT_ROLE_ADMIN?null:$user->id;
        }
        $list = Buyer::queryAll($accountId);
        return $this->ok($list);
    }

    public function servicePay(Request $request){
        $servicesPay = $request->post('services_pay');
        $id = $request->post('id');
        $m  = Buyer::setServicePay($id,$servicesPay);
        return $this->ok();

    }

    /**
     * 更改买家所属于网络中介
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * User: Tank
     * Date: 2019/8/23
     * Time: 15:24
     */
    public function brokerChange(Request $request){
        $id = $request->input('id');
        $accountId = $request->input('account_id');
        $r = Buyer::changeBroker($id,$accountId);
        return $this->ok();
    }


    /**
     * 更改中介时，给出的中介列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * User: Tank
     * Date: 2019/8/23
     * Time: 15:49
     */
    public function brokerQuery(Request $request){
        $q = $request->input('q');
        $user = $this->guard()->user();
        $accountId = $user->role==Consts::ACCOUNT_ROLE_ADMIN?null:$user->id;
        $list = Buyer::buyerBrokerQuery($q,$accountId);
        return $this->ok($list);
    }


}
