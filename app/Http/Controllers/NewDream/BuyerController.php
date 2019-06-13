<?php

namespace App\Http\Controllers\NewDream;

use App\Exceptions\BaseException;
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
        $m = Buyer::updateItem($id,$param);
        return $this->ok($m);
    }

    public function Delete(Request $request){
        $id = $request->input('id');
        $m = Buyer::deleteItem($id);
        return $this->ok();
    }

    public function List(Request $request){
        $param = $request->post();
        $list = Buyer::listItem($param);
        return $this->ok($list);
    }

    public function Show(Request $request){
        $id = $request->input('id');
        $user = $this->guard()->user();
        $m = Buyer::find($id);
        $m->accessCheck($user);
        if($m){
            return $this->ok($m);
        }
        throw new BaseException(Consts::NO_RECORD_FOUND);
    }
}
