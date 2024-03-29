<?php

namespace App\Http\Controllers\NewDream;

use App\Events\BusinessEmailEvent;
use App\Exceptions\BaseException;
use App\Models\Business;
use App\Models\Buyer;
use App\Models\RecommendToBuyerBroker;
use App\Models\RecommendToBuyerBrokerDetail;
use App\Models\UploadFile;
use App\Traits\Consts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use PDF;

class RecommendController extends BaseController
{

    public function List(Request $request)
    {
        $q = $request->input('q');
        $accountId = $this->getAdminAccountId();
        $list = RecommendToBuyerBroker::list($q,$accountId);
        return $this->ok($list);
    }

    public function Add(Request $request)
    {
        $param = $request->post();
        $accountId = $this->guard()->id();
        $param['created_by'] = $accountId;
        $m = RecommendToBuyerBroker::addItem($param);
        return $m;
    }

    public function Update(Request $request)
    {
        $param = $request->post();
        $m = RecommendToBuyerBroker::updateItem($param);
        return $this->ok($m);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     * User: Tank
     * Date: 2019/8/28
     * Time: 9:18
     */
    public function Delete(Request $request)
    {
        $id = $request->input('id');
        $accountId = $this->guard()->id();
        $r = RecommendToBuyerBroker::deletedItem($id,$accountId);
        return $this->ok($r);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * User: Tank
     * Date: 2019/8/28
     * Time: 9:19
     */
    public function Remove(Request $request)
    {
        $ids = $request->post('ids');
        $recommendId = $request->post('recommend_id');
        $accountId = $this->guard()->id();
        $r = RecommendToBuyerBroker::removeItems($recommendId,$ids);
        return $this->ok($r);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws BaseException
     * User: Tank
     * Date: 2019/8/28
     * Time: 9:53
     */
    public function Append(Request $request)
    {
        $ids = $request->post('ids');
        $recommendId = $request->post('recommend_id');
        if(!$recommendId || !$ids){
            throw new BaseException(Consts::DATA_VALIDATE_WRONG);
        }
        $r = RecommendToBuyerBroker::appendItems($ids,$recommendId);
        return $this->ok($r);
    }


    public function Details(Request $request){
        $id = $request->post('recommend_id');
        $business = RecommendToBuyerBroker::getDetailBusiness($id);
        return $this->ok($business);
    }

    public function Query(Request $request){
        $list = RecommendToBuyerBroker::getRecommendQuery();
        return $list;
    }

    public function generatePdf(Request $request, $level = 1 ){
        if(!in_array($level,['1','2','3','4'])){
            throw new BaseException(Consts::PARAM_VALIDATE_WRONG);
        }
        $recommendId = $request->post('id');
        $user = $this->guard()->user();
        $fileName = $user->id . '/Business('.date('Y-m-d').').pdf';
        $businessIds = RecommendToBuyerBrokerDetail::select('business_id')
            ->where('recommend_id',$recommendId)->get()->toArray();
        $ids = array_column($businessIds,'business_id');
        $business = Business::getBusinessLevel($ids,$level);
        $pdf = PDF::loadView('pdf.business_level_one',['business' =>$business]);
        $pdf->setOptions(['isPhpEnabled'=> true,'dpi' => 96]);
        $pdf->setPaper('a4');
        $r = UploadFile::saveS3TempPdf($fileName,$pdf->output());
        if($r){
            $url = UploadFile::getS3TempPdf($fileName);
            return $this->ok(['url' => (string)$url]);
        }
        return $this->err(Consts::SAVE_FILE_ERROR);
//        return $pdf->stream($fileName);
    }

    public function generateEmailPdf(Request $request, $level = 1 ){
        if(!in_array($level,['1','2','3','4'])){
            throw new BaseException(Consts::PARAM_VALIDATE_WRONG);
        }

        $buyerId = $request->input('buyer_id');
        if(!$buyerId){
            throw new BaseException(Consts::PARAM_VALIDATE_WRONG);
        }
        $buyer = Buyer::find($buyerId);
        if(!$buyer){
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        $email = $buyer->email;

        $recommendId = $request->post('id');
        $user = $this->guard()->user();
        $fileName = $user->id . '/recommend/Business('.date('Y-m-d').').pdf';
        $businessIds = RecommendToBuyerBrokerDetail::select('business_id')
            ->where('recommend_id',$recommendId)->get()->toArray();
        $ids = array_column($businessIds,'business_id');
        $business = Business::getBusinessLevel($ids,$level);
        $pdf = PDF::loadView('pdf.business_level_one',['business' =>$business]);
        $pdf->setOptions(['isPhpEnabled'=> true,'dpi' => 96]);
        $pdf->setPaper('a4');
        $r = Storage::disk('temp')->put($fileName,$pdf->output());
        if($r){
            event(new BusinessEmailEvent($fileName,$email));
            return $this->ok();
        }
        return $this->err(Consts::SAVE_FILE_ERROR);
//        return $pdf->stream($fileName);
    }
}
