<?php

namespace App\Models;

use App\Exceptions\BaseException;
use App\Traits\Consts;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{

    protected $guarded = ['id'];
    public $timestamps = false;

    public static function listItem()
    {
        $query = self::select("*")->whereNull("deleted_at");
        $list = $query->paginate(15);
        return $list;
    }


    public static function addItem($param = Array())
    {
        $param['created_at'] = new Carbon();

        $m = self::create($param);
        if (!$m) {
            throw new BaseException(Consts::SAVE_RECORD_FAILED);
        }
        return $m;
    }

    public static function delItem($ids)
    {
        $update = self::whereIn('id', $ids)->update(['deleted_at' => new Carbon()]);
        return $update;
    }
}
