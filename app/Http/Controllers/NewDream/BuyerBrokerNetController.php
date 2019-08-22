<?php

namespace App\Http\Controllers\NewDream;

use App\Exceptions\BaseException;
use App\Models\BuyerBrokerNet;
use App\Models\BuyerBrokerNetMember;
use Illuminate\Http\Request;

class BuyerBrokerNetController extends BaseController
{
    public function Add(Request $request){
        $param = $request->post();
        $accountId = $this->guard()->id();
        $m = BuyerBrokerNet::addItem($param,$accountId);
        return $this->ok($m);
    }

    public function Show(Request $request)
    {
        $id = $request->post('id');
        $m = BuyerBrokerNet::find($id);
        if(!$m){
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        $m->withNetBroker();
        $m->withFreeNetBroker(); // change it to multiple broker net
        return $this->ok($m);
    }

    public function List(Request $request)
    {
        $param = $request->post();
        $list = BuyerBrokerNet::getList($param);
        return $this->ok($list);
    }

    public function Delete(Request $request)
    {
        $id = $request->post('id');
        if(!$id){
            throw new BaseException(Consts::PARAM_VALIDATE_WRONG);
        }
        $accountId = $this->guard()->id();
        $m = BuyerBrokerNet::delItem($id,$accountId);
        return $this->ok();
    }

    public function Update(Request $request)
    {
        $id = $request->post('id');
        $param = $request->post();
        $m = BuyerBrokerNet::updateItem($id,$param);
        return $this->ok($m);
    }

    public function getMember(Request $request){
        $netId = $request->get('net_id');
        $list = BuyerBrokerNetMember::getMember($netId);
        return $this->ok($list);
    }

    public function setManager(Request $request){
        $id = $request->post('id');
        $status = $request->post('manager');
        $status = $status=='true'?1:0;
        $m = BuyerBrokerNetMember::setManager($id,$status);
        return $this->ok();
    }

    public function brokers(Request $request){
        $list = BuyerBrokerNetMember::getFreeBuyerBroker(null);
        return $this->ok($list);
    }





}
