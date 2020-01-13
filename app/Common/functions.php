<?php
/**
 * Created by PhpStorm.
 * Author: Tank
 * User: Tank
 * Date: 2019/6/25
 * Time: 14:49
 */

function generateOrderNo($accountId){
    $order = date('Ymd' . str_pad($accountId, 5, '0', STR_PAD_LEFT) . 'sHi');
    return $order;
}

function create_guid($namespace = '')
{
    static $guid = '';
    $uid = uniqid("", true);
    $data = $namespace;
    $data .= $_SERVER['REQUEST_TIME'];
    $data .= $_SERVER['HTTP_USER_AGENT'];
    $data .= $_SERVER['REMOTE_PORT'];
    $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
    $guid = '{' .
        substr($hash, 0, 8) .
        '-' .
        substr($hash, 8, 4) .
        '-' .
        substr($hash, 12, 4) .
        '-' .
        substr($hash, 16, 4) .
        '-' .
        substr($hash, 20, 12) .
        '}';
    return $guid;
}

function getBusinessStatus($num = null){
    $array = [
        1 => 'Yes',
        2 => 'No',
        3 => 'NA',
    ];
    return isset($array[$num])?$array[$num]:$array[3];
}

function getIdsFromString($str,&$ids){
    $pattern = '/\d+/';
    if(preg_match_all($pattern, $str, $ids)){
        $ids = $ids[0];
        return  true;
    }else{
        return false;
    }
}

function getDateUnit($num){
    $array =[
        1 => 'week',
        2 => 'month',
        3 => 'quarter',
        4 => 'year',
    ];
    return isset($array[$num])?$array[$num]:null;
}

function getRoles($index = 1, $all = false){
    $array = [
        1 => 'Intention buyer',
        2 => 'Buyer advisor',
        3 => 'Seller intermediary',
    ];
    if($all) return $array;
    if(isset($array[$index])){
        return $array[$index];
    }
    return 'None';
}

function rep($data,$code=1001,$status=200,$headers=[]){
    if($data === false && $code != 1001){
        $data = [];
    }
    $response = Array(
        'code' => $code,
        'msg' => __('const.'.$code),
        'data' => $data,
    );
    return response($response,$status,$headers);
}

function immigrationSupportTags($withKeys=false){
    $array = [
      ['label' => 'L1', 'value'=>1],
      ['label' => 'EB1-C', 'value'=>2],
      ['label' => 'E2', 'value'=>3],
    ];
    if($withKeys){
        $collection = collect($array);
        $array = $collection->mapWithKeys(function ($item) {
            return [$item['label'] => $item['value']];
        });
        $array->all();
    }
    return $array;
}
