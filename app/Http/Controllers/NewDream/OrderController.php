<?php

namespace App\Http\Controllers\NewDream;

use App\Exceptions\BaseException;
use App\Models\Order;
use App\Traits\Consts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends BaseController
{

    public function Add(Request $request){
        $param = $request->post();
        if(!(isset($param['buyer_id']) && $param['buyer_id'])){
            throw new BaseException(Consts::DATA_VALIDATE_WRONG);
        }
        $accountId = $this->guard()->id();
        $m = Order::addItem($param,$accountId);
        return $this->ok($m);
    }

    public function Update(Request $request){
        $param = $request->except('id');
        $id = $request->input('id');
        $user = $this->guard()->user();
        Order::accessCheck($id,$user);
        $m = Order::updateItem($id,$param);
        return $this->ok($m);
    }

    public function Delete(Request $request){
        $id = $request->input('id');
        $m =  Order::deleteItem($id);
        if($m){
            return $this->ok();
        }
        return $this->err(Consts::SAVE_RECORD_FAILED);
    }

    public function List(Request $request){
        $param = $request->input();
        $buyerId = $request->input('buyer_id',null);
        $user = $this->guard()->user();
        $accountId = $user->role==Consts::ACCOUNT_ROLE_ADMIN?null:$user->id;
        $list = Order::listItem($param,$buyerId,$accountId);
        return $this->ok($list);
    }

    public function Show(Request $request){
        $id = $request->input('id');
        if(!$id){
            throw new BaseException(Consts::DATA_VALIDATE_WRONG);
        }
        $m = Order::getItemByBuyerId($id);
        return $this->ok($m);
    }

    public function Audit(Request $request){
        $id = $request->input('id');
        $accountId = $this->guard()->id();
        $m = Order::auditItem($id,$accountId);
        return $this->ok();
    }

    public function Status(Request $request){
        $id = $request->input('id');
        $status = $request->input('status');
        if(!($id || $status)){
            throw new BaseException(Consts::DATA_VALIDATE_WRONG);
        }
        $m = Order::updateStatus($id,$status);
        return $this->ok();
    }

    public function View(Request $request){
        $id = $request->input('id');
        if(!$id){
            throw new BaseException(Consts::DATA_VALIDATE_WRONG);
        }
        $m = Order::getDetailList($id);
        return $this->ok($m);
    }

}
