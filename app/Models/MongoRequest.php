<?php
/**
 * Created by PhpStorm.
 * Author: Tank
 * User: Tank
 * Date: 2019/9/27
 * Time: 10:52
 */
namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class MongoRequest extends Model
{
    protected $connection = "mongodb_log";
//    protected $table = 'user';
    protected $collection = 'request';

    public static function getListByAccount($accountId = null,$param = []){
        $query = self::select(self::getColumnsByUser());
        if($accountId){
            $query->where('user_id',$accountId);
        }
        $query->where($param);
        $list = $query->orderBy('created_at','DESC')->paginate(15);
        return $list;
    }


    public static function getColumnsByUser(){
        return Array(
            '_id',
            'user_id',
            'url',
            'method',
            'is_ajax',
            'agent',
            'created_at',
            'x-forwarded-for',
//            'x-real-ip',
        );
    }
}