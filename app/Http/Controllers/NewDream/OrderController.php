<?php

namespace App\Http\Controllers\NewDream;

use App\Exceptions\BaseException;
use App\Models\BuyerOrderUploadFile;
use App\Models\Order;
use App\Models\OrderPayInfo;
use App\Models\UploadFile;
use App\Traits\Consts;
use PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends BaseController
{

    public function Add(Request $request){
        $param = $request->post();
        if(!(isset($param['buyer_id']) && $param['buyer_id'])){
            throw new BaseException(Consts::DATA_VALIDATE_WRONG);
        }
        $accountId = $this->guard()->id();
        $m = Order::addItem($param,$accountId);
        return $this->ok($m);
    }

    public function Update(Request $request){
        $param = $request->except('id');
        $id = $request->input('id');
        $user = $this->guard()->user();
        Order::accessCheck($id,$user);
        $m = Order::updateItem($id,$param);
        return $this->ok($m);
    }

    public function Delete(Request $request){
        $id = $request->input('id');
        $m =  Order::deleteItem($id);
        if($m){
            return $this->ok();
        }
        return $this->err(Consts::SAVE_RECORD_FAILED);
    }

    public function List(Request $request){
        $param = $request->input();
        $buyerId = $request->input('buyer_id',null);
        $user = $this->guard()->user();
        $accountId = $user->role==Consts::ACCOUNT_ROLE_ADMIN?null:$user->id;
        $list = Order::listItem($param,$buyerId,$accountId);
        return $this->ok($list);
    }

    public function Show(Request $request){
        $id = $request->input('id');
        if(!$id){
            throw new BaseException(Consts::DATA_VALIDATE_WRONG);
        }
        $m = Order::getItemByBuyerId($id);
        return $this->ok($m);
    }

    public function Audit(Request $request){
        $id = $request->input('id');
        $status = $request->input('status');
        $accountId = $this->guard()->id();
        $m = Order::auditItem($id,$status,$accountId);
        return $this->ok();
    }

    public function Status(Request $request){
        $id = $request->input('id');
        $status = $request->input('status');
        $reason = $request->input('reason');
        if(!($id || $status)){
            throw new BaseException(Consts::DATA_VALIDATE_WRONG);
        }
        $m = Order::updateStatus($id,$status,$reason);
        return $this->ok();
    }


    public function View(Request $request){
        $id = $request->input('id');
        if(!$id){
            throw new BaseException(Consts::DATA_VALIDATE_WRONG);
        }
        $user = $this->guard()->user();
        Order::accessCheck($id,$user);
        $m = Order::getDetailList($id);
        return $this->ok($m);
    }

    public function loadOrderPdf(Request $request){
        $orderId = $request->input('order_id');
        if(!$orderId){
            throw new BaseException(Consts::DATA_VALIDATE_WRONG);
        }
        $user = $this->guard()->user();
//        Order::accessCheck($orderId,$user);
        $m = Order::getDetailList($orderId);
        $business = $m->details;
        if($business->isEmpty()){
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
//        return view('pdf.business_level_one',['business' =>$business]);
        $fileName = 'business('.date('Y-m-d').').pdf';
        $pdf = PDF::loadView('pdf.business_level_one',['business' =>$business]);
        $pdf->setOptions(['isPhpEnabled'=> true,'dpi' => 96]);
        $pdf->setPaper('a4');
        return $pdf->stream($fileName);
    }

    public function addPayInformation(Request $request){
//        $orderId = $request->input('order_id');
//        $payment = $request->input('payment');
        $param = $request->input();
        $m = OrderPayInfo::addItem($param);
        return $this->ok($m);
    }

    public function delPayInformation(Request $request){
        $payId =  $request->input('id');
        $del = OrderPayInfo::delItem($payId);
        return $this->ok();
    }

    public function showPayInformation(Request $request){
        $orderId = $request->input('id');
        $payment = OrderPayInfo::getList($orderId);
        $files = BuyerOrderUploadFile::getList($orderId);
        return $this->ok(compact('payment','files'));
    }

    public function uploadOrderPayInformation(Request $request){
        $orderId =  $request->input('order_id');
        $file = $request->file('file');
        if(!$file || !$orderId){
            $this->paramValidateWrong();
        }
        $file->remark = $request->input('remark');
        $file = UploadFile::saveFile($file,'upload');
        $m = BuyerOrderUploadFile::addItem(['order_id'=>$orderId,'file_id'=>$file->id]);
        return $this->ok();
    }

    public function deleteOrderPayInformation(Request $request){
        $id =  $request->input('id');
        if(!$id){
            $this->paramValidateWrong();
        }
        BuyerOrderUploadFile::deleteItem($id);
        return $this->ok();
    }

    public function showOrderPayInformation(Request $request){
        $id =  $request->input('file_id');
        $file = UploadFile::find($id);
        if(!$file){
            $this->paramValidateWrong();
        }
        $stream = $file->downLoad();
        return $stream;
    }

}
