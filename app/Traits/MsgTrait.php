<?php
/**
 * Created by PhpStorm.
 * Author: Tank
 * User: win
 * Date: 2018/6/13
 * Time: 15:18
 */

namespace App\Traits;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;


trait MsgTrait {

    public function ok($data=Array(),$msg='',$extra=Array()){
        $code = Consts::SUCCESS;
        $msg = __('const.'.$code);
        return $this->responseJson($code,$data,$extra,$msg);
    }

    public function err($code,$msg='',$data=Array(),$extra=Array()){
        if(!$msg){
            $msg = __('const.'.$code);
        }
        return $this->responseJson($code,$data,$extra,$msg);
    }

    /**
     * 快速返回信息
     * @param $code
     * @param string $msg
     */
    public function r($msg = '',$code=Consts::SUCCESS){
        if($msg === true || $msg ==''){
            $msg = Consts::getUserMsg($code);
        }
        return $this->responseJson($code,[],[],$msg);
    }

    public function responseJson($code,$data=Array(),$extra=Array(),$msg=''){
        $response = Array(
            'code' => $code,
            'msg' =>  $msg,
            'data' => $data,
            'extra' => Array(
                'version' => '1.0'
            ),
        );
        $response['extra'] = array_merge($response['extra'],$extra);
        return response()->json($response);
    }

    /**
     * 自定方记录！
     * @param $msg
     */
    public function lg($msg){
        $m = new Logger("data");
        $hd = new StreamHandler(storage_path().'/logs/data_log.log',Logger::INFO);
        $m->pushHandler($hd);
        $m->error($msg);
    }


}