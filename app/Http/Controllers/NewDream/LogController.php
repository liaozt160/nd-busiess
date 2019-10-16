<?php

namespace App\Http\Controllers\NewDream;


use App\Models\Account;
use App\Models\MongoRequest;
use Illuminate\Http\Request;

class LogController extends BaseController
{
    public function index(Request $request){
        $user = $this->guard()->user();
        $accountId = $user->role == 1?null:$user->id;

        $param = [];
        if($userId = $request->input('user_id')){
            $param['user_id'] = (int)$userId;
        }
        $list = MongoRequest::getListByAccount($accountId,$param);
        $accounts = Account::accountQuery();
        $list->transform(function ($item, $key) use ($accounts) {
            $user = $accounts->firstWhere('user_id',$item->user_id);
            if($user){
                $item->user_name=$user->name;
                $item->email=$user->email;
            }else{
                $item->user_name = 'guest';
                $item->email = '';
            }
            return $item;
        });
        return $this->ok($list);
    }
}
