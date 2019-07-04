<?php

namespace App\Http\Controllers\NewDream;

use App\Events\PayAttention;
use App\Exceptions\BaseException;
use App\Mail\CreateUser;
use App\Models\Account;
use App\Models\Business;
use App\Models\BusinessAssign;
use App\Models\BusinessAttention;
use App\Models\Buyer;
use App\Traits\ApiTrait;
use App\Traits\Consts;
use App\Traits\MsgTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
        $param = $request->post();
        $list = Account::getList($param);
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
        $business = $request->post('assigned');
        $business = (substr($business,1,strlen($business)-2));
        $business = explode(',',$business);
        $accountId = $request->post('account_id');
        $list = BusinessAssign::addItems($accountId,$business);
        return $this->ok($list);
    }

    /**
     *  公司分配
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * User: Tank
     * Date: 2019/6/19
     * Time: 15:05
     */
    public function businessAssignList(Request $request){
        $accountId = $request->input('id');
        $list = BusinessAssign::getAssignList($accountId);
        return $this->ok($list);
    }

    public function businessAssignListTo(Request $request){
        $accountId = $request->input('id');
        $list = BusinessAssign::getAssignListTo($accountId);
        $query = Business::getQueryAll();
        return $this->ok(['business'=>$query,'assigned' => array_values(array_column($list->toArray(),'business_id'))]);
    }


    public function businessAttentionList(Request $request){
        $accountId = $request->post('account_id');
        $param = $request->post();
        $accountId = $this->guard()->id();
        $list = BusinessAttention::getList($param,$accountId);
        return $this->ok($list);
    }

    public function accountPassword(Request $request){
        $accountId = $request->input('account_id');
//        $oldPassword = $request->input('old_password');
        $newPassword = $request->input('new_password');
        $m = Account::passwordUpdate($accountId,$newPassword);
        return $this->ok();
    }

    public function profilePassword(Request $request){
        $accountId = $this->guard()->id();
        $oldPassword = $request->input('old_password');
        $newPassword = $request->input('new_password');
        $m = Account::passwordUpdate($accountId,$newPassword,$oldPassword);
        return $this->ok();
    }

    public function test(){
//        $log = Log::critical('aaaaaaaaa');
//        var_dump($log);
        throw new BaseException(1,'dddddddddd');
        return $this->ok();
        exit;
        $account = $this->guard()->user();
        $m =  BusinessAttention::find(1);
        event(new PayAttention($m));
//        $m = Mail::to('tank@ylbservices.com')->send(new CreateUser());
        return $this->ok();
    }


    public function dashboard(){
        $role = $this->guard()->user()->role;
        if($role == Consts::ACCOUNT_ROLE_ADMIN){
            return $this->ok($this->dashboardAdmin());
        }
        if($role == Consts::ACCOUNT_ROLE_BUYER_BROKER){
            $id = $this->guard()->id();
            return $this->ok($this->dashboardBuyer($id));
        }
        if($role == Consts::ACCOUNT_ROLE_BUSINESS_BROKER){
            $id = $this->guard()->id();
            return $this->ok($this->dashboardBusiness($id));
        }
    }

    /**
     * 管理员中心
     * @return array
     * User: Tank
     * Date: 2019/6/24
     * Time: 14:10
     */
    protected function dashboardAdmin(){
        $business = Business::businessSum(null,Consts::BUSINESS_STATUS_NORMAL);
        $businessSaled = Business::businessSum(null,Consts::BUSINESS_STATUS_SOLD);
        $buyers = Buyer::buyerSum(null,Consts::BUSINESS_STATUS_NORMAL);
        $attentions = BusinessAttention::getListSumByBusiness();
        $buyerBroker = Account::getSumByBuyerBroker();
        $businessBroker = Account::getSumByBusinessBroker();
        return compact('business','businessSaled','buyers','attentions','buyerBroker','businessBroker');
    }

    /**
     * 买家中心
     * @param $accountId
     * @return array
     * User: Tank
     * Date: 2019/6/24
     * Time: 14:09
     */
    protected function dashboardBusiness($accountId){
        $business = Business::businessSum($accountId,Consts::BUSINESS_STATUS_NORMAL);
        $businessSaled = Business::businessSum($accountId,Consts::BUSINESS_STATUS_SOLD);
        $attentions = BusinessAttention::getListSumByBusiness($accountId);
        return compact('business','businessSaled','attentions');
    }

    /**
     * 买家中心
     * @param $id
     * @return array
     * User: Tank
     * Date: 2019/6/24
     * Time: 14:09
     */
    protected function dashboardBuyer($id){
        $business = Business::businessSum(null,Consts::BUSINESS_STATUS_NORMAL);
        $buyers = Buyer::buyerSum($id,Consts::BUSINESS_STATUS_NORMAL);
        $attentions = BusinessAttention::getListSumByBuyer($id);
        return compact('buyers','attentions','business');
    }


}
