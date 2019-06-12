<?php
/**
 * Created by PhpStorm.
 * Author: Tank
 * User: win
 * Date: 2018/10/17
 * Time: 11:05
 */
namespace App\Traits;


trait ApiTrait {
    protected function guard()
    {
        return auth()->guard('nd');
    }
}