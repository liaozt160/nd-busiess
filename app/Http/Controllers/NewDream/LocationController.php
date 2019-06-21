<?php

namespace App\Http\Controllers\NewDream;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends  BaseController
{
    public function query(Request $request){
        $code = $request->input('code','0');
        $lang = $request->input('lang','en');
        $list = Location::queryChild($code,$lang);
        return $this->ok($list);
    }

}
