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

class MongoContact extends Model
{
    protected $connection = "mongodb";
//    protected $table = 'user';
    protected $collection = 'my-contact';

    public static function insertItem($param){
        $m = self::insert($param);
        return $m;
    }
}