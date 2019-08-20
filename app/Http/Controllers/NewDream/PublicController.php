<?php

namespace App\Http\Controllers\NewDream;

use App\Models\BusinessCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

class PublicController extends BaseController
{
    public function getCategoryQuery(Request $request){
        $list = BusinessCategory::getQuery(App::getLocale());
        return $this->ok($list);
    }
}
