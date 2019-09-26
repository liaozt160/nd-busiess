<?php

namespace App\Listeners;

use App\Events\PayAttention;
use App\Events\RequestEvent;
use App\Mail\PayAttentionSystem;
use App\Mail\PayAttentionTo;
use App\Models\Logger;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RequestSubscriber implements ShouldQueue
{

    public function onRequest(RequestEvent $event){
        $loggers = $event->logger;
        foreach ($loggers as $key =>$log){
            if(is_array($log) || is_object($log)){
                $loggers[$key] = json_encode($log);
            }
        }
//        dd($loggers);
        $log  = Logger::create($loggers);
        dd($log->id);
    }


    public function subscribe($events)
    {
        $events->listen(
            'App\Events\RequestEvent',
            'App\Listeners\RequestSubscriber@onRequest'
        );
    }
}
