<?php

namespace App\Models;

use App\Exceptions\BaseException;
use App\Traits\Consts;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BuyerOrderUploadFile extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    public static function addItem($param=[]){
        $m = self::create($param);
        if($m){
            return $m;
        }
        throw new BaseException(Consts::SAVE_RECORD_FAILED);
    }

    public static function getList($orderId){
        $columns = [
            'o.id','o.file_id','f.name','f.remark','f.created_at'
        ];
        $list = self::select($columns)
            ->from('buyer_order_upload_files as o')
            ->leftjoin('upload_files as f','o.file_id','f.id')
            ->where('o.order_id',$orderId)->get();
        return $list;
    }

    public static function deleteItem($id){
        $m = self::find($id);
        if(!$m){
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        $file = UploadFile::find($m->file_id);
        if(!$file){
            throw new BaseException(Consts::NO_RECORD_FOUND);
        }
        DB::beginTransaction();
         try{
             Storage::disk($file->disk)->delete($file->file);
             $file->delete();
             $m->delete();
             DB::commit();
         }catch (\Exception $e){
             throw new BaseException(Consts::UNKNOWN_ERROR,$e->getMessage());
         }
    }

}
