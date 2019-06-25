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