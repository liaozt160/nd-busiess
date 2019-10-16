<?php

namespace App\Listeners;

use App\Events\PayAttention;
use App\Events\RequestEvent;
use App\Mail\PayAttentionSystem;
use App\Mail\PayAttentionTo;
use App\Models\Logger;
use App\Models\MongoRequest;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Debug\Exception;

class RequestSubscriber implements ShouldQueue
{
    public $tries = 2;

    public function onRequest(RequestEvent $event)
    {
        try{
        $loggers = $event->logger;
//        foreach ($loggers as $key => $log) {
//            if (is_array($log) || is_object($log)) {
//                $loggers[$key] = json_encode($log);
//            }
//        }
        $m = MongoRequest::insert($loggers);
//        $log = Logger::create($loggers);
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }


    public function subscribe($events)
    {
        $events->listen(
            'App\Events\RequestEvent',
            'App\Listeners\RequestSubscriber@onRequest'
        );
    }

    /**
     * param is the trigger event
     * @param RequestEvent $event
     * User: Tank
     * Date: 2019/9/29
     * Time: 11:17
     */
    public function failed($event)
    {

    }

}
