<?php

namespace App\Http\Controllers\NewDream;

use App\Exceptions\BaseException;
use App\Models\Account;
use App\Models\BusinessAssign;
use App\Traits\ApiTrait;
use App\Traits\Consts;
use App\Traits\MsgTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountController extends BaseController
{
    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\BaseException
     * User: Tank
     * Date: 2019/6/11
     * Time: 16:19
     */
    public function login(Request $request){
        $param = $request->only(['email','password']);
        $credentials = [
            'deleted_at' => null,
            'status' => Consts::ACCOUNT_ROLE_ADMIN,
        ];
        $credentials = array_merge($credentials,$param);
        $token = $this->guard()->attempt($credentials);
        if($token){
            return $this->ok(['access_token' => $token]);
        }
        throw new BaseException(Consts::ACCOUNT_LOGIN_FAILED);
    }

    /**
     *  add account
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\BaseException
     * User: Tank
     * Date: 2019/6/11
     * Time: 17:37
     */
    public function accountAdd(Request $request){
        $param = $request->post();
        $m = Account::addAccount($param);
        return $this->ok($m);
    }

    /**
     * @param Request $request
     * User: Tank
     * Date: 2019/6/11
     * Time: 17:38
     */
    public function accountDelete(Request $request){
        $id = $request->input('id');
        $m = Account::accountDelete($id);
        if($m){
            return $this->ok();
        }
        return $this->err(Consts::SAVE_RECORD_FAILED);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * User: Tank
     * Date: 2019/6/11
     * Time: 18:53
     */
    public function accountUpdate(Request $request){
        $id = $request->input('id');
        $param = $request->post();
        $m = Account::accountUpdate($id,$param);
        if($m){
            return $this->ok($m);
        }
        return $this->err(Consts::SAVE_RECORD_FAILED);
    }
    /**
     * @param Request $request
     * User: Tank
     * Date: 2019/6/11
     * Time: 17:38
     */
    public function accountList(Request $request){
        $list = Account::getList();
        return $this->ok($list);
    }

    public function accountShow(Request $request){
        $id = $request->input('id');
        $m = Account::find($id);
        if($m){
            return $this->ok($m);
        }
        throw new BaseException(Consts::UNKNOWN_ERROR);
    }

    public function profile(Request $request){
        $user = $this->guard()->user();
        return $this->ok($user);
    }

    public function accountStatus(Request $request){
        $id = $request->input('id');
        $status = $request->input('status',1);
        $m = Account::updateStatus($id,$status);
        return $this->ok($m);
    }

    public function businessAssign(Request $request){
        $business = $request->post('business');
        $accountId = $request->post('account_id');
        $list = BusinessAssign::addItems($accountId,$business);
        return $this->ok($list);
    }

    public function businessAssignList(Request $request){
        $accountId = $request->post('account_id');
        $list = BusinessAssign::getAssignList($accountId);
        return $this->ok($list);
    }

}
