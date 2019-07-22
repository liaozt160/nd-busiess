<?php

namespace App\Models;

use App\Exceptions\BaseException;
use App\Traits\Consts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadFile extends Model
{
    protected $guarded = ['id'];

    public static function saveFile($file,$disk='public',$dir=false){
        $param = self::getParamFromFile($file);
        if($dir !== false){
            $param['file'] = $dir .'/'. $param['file'];
        }
        try{
            $save = Storage::disk($disk)->put($param['file'], file_get_contents($file->getRealPath()));
        }catch (\Exception $e){
            Log::info($e->getMessage());
            throw new BaseException(Consts::SAVE_FILE_ERROR,$e->getMessage());
        }
        $param['disk'] = $disk;
        $param['path'] = $dir;
        $m = self::create($param);
        return $m;
    }

    public static function getParamFromFile($file){
        $param['name'] = $file->getClientOriginalName();
//        $param['realPath'] = $file->getRealPath();
        $param['mime_type'] = $file->getClientMimeType();
        $param['extension'] = $file->getClientOriginalExtension();
        $param['file'] =  create_guid().'-'.$file->getClientOriginalName();
        if(isset($file->remark)){
            $param['remark'] =  $file->remark;
        }
        return $param;
    }

    public function downLoad(){
//        header('Content-type: '.$this->mime_type);
//        header("Content-Disposition", "inline;fileName=".$this->name);
        return Storage::disk($this->disk)->download($this->file,$this->name);
    }
}
