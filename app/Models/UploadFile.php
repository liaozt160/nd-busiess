<?php

namespace App\Models;

use App\Exceptions\BaseException;
use App\Traits\Consts;
use Aws\S3\S3Client;
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

    public static function getParamFromFile($file,$s3=false){
        $param['name'] = $file->getClientOriginalName();
//        $param['realPath'] = $file->getRealPath();
        $param['mime_type'] = $file->getClientMimeType();
        $param['extension'] = $file->getClientOriginalExtension();
        if($s3){
            $param['file'] =  create_guid().'/'.$file->getClientOriginalName();
        }else{
            $param['file'] =  create_guid().'-'.$file->getClientOriginalName();
        }

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

    public static function getS3TempPdf($name){
        $prefix = 'temp/';
        $name = $prefix.$name;
        $bucket = env('AWS_BUCKET');
        $s3Client = self::getS3Client(env('AWS_DEFAULT_REGION'));
        $cmd = $s3Client->getCommand('GetObject',[
            'Bucket' => $bucket,
            'Key' => $name
        ]);
        $request = $s3Client->createPresignedRequest($cmd,'+6 minutes');
        $url = $request->getUri();
        return $url;
    }

    public static function saveS3TempPdf($name,$content){
        $disk = Storage::disk('s3');
        $prefix = 'temp/';
        $r = $disk->put($prefix.$name,$content);
        return $r;
    }

    /**
     * generate s3Client
     * @return S3Client
     * User: Tank
     * Date: 2019/10/22
     * Time: 15:48
     */
    public static function getS3Client($region){
        $s3Client = new S3Client([
//            'profile' => 'default',
            'region' => $region,
            'version' => '2006-03-01',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
        return $s3Client;
    }

    public static function s3SaveOrderPayment($file,$disk='s3data'){
        $dir = env('APP_ENV'). '/order';
        return self::s3SaveFile($file,$disk,$dir);
    }

    public static function s3SaveFile($file,$disk='s3data',$dir=false){
        $param = self::getParamFromFile($file,true);
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

    public function getS3Url(){
        $s3Client = self::getS3Client(env('AWS_BUCKET_DATA_REGION'));
        $bucket = env('AWS_BUCKET_DATA');
        $cmd = $s3Client->getCommand('GetObject',[
            'Bucket' => $bucket,
            'Key' => $this->file,
        ]);
        $request = $s3Client->createPresignedRequest($cmd,'+6 minutes');
        $url = $request->getUri();
        return (String)$url;
    }
}
