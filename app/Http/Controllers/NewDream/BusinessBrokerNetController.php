<?php

namespace App\Http\Controllers\NewDream;

use App\Exceptions\BaseException;
use App\Models\BusinessBrokerNet;
use App\Models\BusinessBrokerNetMember;
use App\Traits\Consts;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BusinessBrokerNetController extends BaseController
{

    public function Add(Request $request){
        $param = $request->post();
        $accountId = $this->guard()->id();
        $m = BusinessBrokerNet::addItem($param,$accountId);
        return $this->ok($m);
    }

    public function Show(Request $request)
    {
        $id = $request->post('id');
        $m = BusinessBrokerNet::find($id);
        if(!$m){
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        $m->withNetBroker();
//        $m->withFreeNetBroker(); // change it to multiple broker net
        $m->free_brokers = BusinessBrokerNetMember::getAccountIdByManager(null);
        return $this->ok($m);
    }

    public function List(Request $request)
    {
        $param = $request->post();
        $list = BusinessBrokerNet::getList($param);
        return $this->ok($list);
    }

    public function Delete(Request $request)
    {
        $id = $request->post('id');
        if(!$id){
            throw new BaseException(Consts::PARAM_VALIDATE_WRONG);
        }
        $accountId = $this->guard()->id();
        $m = BusinessBrokerNet::delItem($id,$accountId);
        return $this->ok();
    }

    public function Update(Request $request)
    {
        $id = $request->post('id');
        $param = $request->post();
        $m = BusinessBrokerNet::updateItem($id,$param);
        return $this->ok($m);
    }

    public function getMember(Request $request){
        $netId = $request->get('net_id');
        $list = BusinessBrokerNetMember::getMember($netId);
        return $this->ok($list);
    }

    public function setManager(Request $request){
        $id = $request->post('id');
        $status = $request->post('manager');
        $m = BusinessBrokerNetMember::setManager($id,$status);
        return $this->ok();
    }

    public function brokers(Request $request){
        $list = BusinessBrokerNetMember::getFreeBusinessBroker(null);
        return $this->ok($list);
    }

}
