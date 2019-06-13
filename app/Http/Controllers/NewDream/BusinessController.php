<?php

namespace App\Http\Controllers\NewDream;

use App\Exceptions\BaseException;
use App\Models\Business;
use App\Traits\Consts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BusinessController extends BaseController
{

    public function Add(Request $request){
        $param = $request->post();
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
        $param = $request->post();
        $user = $this->guard()->user();
        $accountId = $user->role=Consts::ACCOUNT_ROLE_USER?null:$user->id;
        $list = Business::listItem($param,$accountId);
        return $this->ok($list);
    }

    public function Show(Request $request){
        $accountId = $this->guard()->id();
        $id = $request->input('id');
        $user = $this->guard()->user();
        Business::accessCheck($id,$user);
        $m = Business::find($id);
        if($m){
            return $this->ok($m);
        }
        throw new BaseException(Consts::NO_RECORD_FOUND);
    }
}
