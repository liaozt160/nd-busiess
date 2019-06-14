<?php

namespace App\Http\Controllers\NewDream;

use App\Exceptions\BaseException;
use App\Models\BusinessAttention;
use App\Models\Buyer;
use App\Traits\Consts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BuyerController extends BaseController
{
    public function Add(Request $request){
        $param = $request->post();
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
        $param = $request->post();
        $user = $this->guard()->user();
        $accountId = $user->role=Consts::ACCOUNT_ROLE_USER?null:$user->id;
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
        $m = BusinessAttention::addItemByArray($param);
        return $this->ok($m);
    }

}
