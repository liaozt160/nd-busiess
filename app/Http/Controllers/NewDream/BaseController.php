<?php

namespace App\Http\Controllers\NewDream;

use App\Exceptions\BaseException;
use App\Traits\ApiTrait;
use App\Traits\Consts;
use App\Traits\MsgTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    use MsgTrait, ApiTrait;


    public function logout(Request $request){
        $this->guard()->logout();
        return $this->ok();
    }

    /**
     *  判断获取用户id，管理员的返回null
     * @return null
     * User: Tank
     * Date: 2019/8/23
     * Time: 17:59
     */
    protected function getAdminAccountId(){
        $user = $this->guard()->user();
        $accountId = $user->role==Consts::ACCOUNT_ROLE_ADMIN?null:$user->id;
        return $accountId;
    }

    public function Add(Request $request){
        throw new BaseException(Consts::SUCCESS,'Method not defined');
    }
    public function Update(Request $request){
        throw new BaseException(Consts::SUCCESS,'Method not defined');
    }

    public function Delete(Request $request){
        throw new BaseException(Consts::SUCCESS,'Method not defined');
    }

    public function List(Request $request){
        throw new BaseException(Consts::SUCCESS,'Method not defined');
    }

    public function Show(Request $request){
        throw new BaseException(Consts::SUCCESS,'Method not defined');
    }

    protected function paramValidateWrong(){
        throw new BaseException(Consts::PARAM_VALIDATE_WRONG);
    }
}
