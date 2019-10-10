<?php
/**
 * Created by PhpStorm.
 * Author: Tank
 * User: Tank
 * Date: 2019/9/27
 * Time: 14:11
 */

namespace App\Common;


use MongoDB\Client;
use Monolog\Handler\MongoDBHandler;
use Monolog\Logger;
use Monolog\Processor\WebProcessor;

class CreateCustomLogger
{
    public function __invoke(array $config)
    {
        $logger = new Logger('mongo'); // 创建 Logger
        $handler = new MongoDBHandler( // 创建 Handler
            new Client($config['server']), // 创建 MongoDB 客户端（依赖 mongodb/mongodb）
            $config['database'],
            $config['collection']
        );
        $handler->setLevel($config['level']);
        $logger->pushHandler($handler); // 挂载 Handler
        $logger->pushProcessor(new WebProcessor($_SERVER)); // 记录额外的请求信息
        return $logger;
    }
}