<?php

namespace App\Http\Controllers\NewDream;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends  BaseController
{
    public function query(Request $request){
        $code = $request->input('code','0');
        $list = Location::queryChild($code);
        return $this->ok($list);
    }

}
