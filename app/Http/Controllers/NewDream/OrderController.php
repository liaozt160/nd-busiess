<?php

namespace App\Http\Controllers\NewDream;

use App\Models\Order;
use App\Traits\Consts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends BaseController
{

    public function Add(Request $request){
        $param = $request->post();
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
        $user = $this->guard()->user();
        $accountId = $user->role==Consts::ACCOUNT_ROLE_ADMIN?null:$user->id;
        $list = Order::listItem($param,$accountId);
        return $this->ok($list);
    }

}
