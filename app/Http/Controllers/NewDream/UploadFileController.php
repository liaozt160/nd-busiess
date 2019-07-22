<?php

namespace App\Http\Controllers\NewDream;

use App\Exceptions\BaseException;
use App\Models\UploadFile;
use App\Traits\Consts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadFileController extends BaseController
{

    public function uploadOrderPayInformation(Request $request){
        $file = $request->file('file');
        $param = UploadFile::saveFile($file,'upload');
        var_dump($param);
    }

    function file(Request $request,$id){
        $file = UploadFile::find($id);
        if(!$file){
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        return $file->downLoad();
//        header('Content-type: '.$file->mime_type);
//        header("Content-Disposition", "inline;fileName=".$file->name);
//        $a =  Storage::disk($file->disk)->get($file->file);
//        echo $a;
    }
}
