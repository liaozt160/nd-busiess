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
}