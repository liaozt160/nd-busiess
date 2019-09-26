<?php

namespace App\Http\Controllers\NewDream;

use App\Events\ContactUsEvent;
use App\Exceptions\BaseException;
use App\Models\Contact;
use App\Traits\Consts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactController extends BaseController
{
    public function Add(Request $request)
    {
        $param = $request->post();
        $m = Contact::addItem($param);
        event(new ContactUsEvent($m));
        return $this->ok();
    }

    public function List(Request $request)
    {
        $list = Contact::listItem();
        return $this->ok($list);
    }

    public function Delete(Request $request)
    {
        $id = $request->input("ids",null);
        if(!$id){
            throw new BaseException(Consts::PARAM_VALIDATE_WRONG);
        }
        $del = Contact::delItem($id);
        return $this->ok();
    }
}
