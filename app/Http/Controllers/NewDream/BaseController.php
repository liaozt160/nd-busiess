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

}
