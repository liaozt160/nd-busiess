<?php

namespace App\Listeners;

use App\Events\PayAttention;
use App\Events\SystemEvent;
use App\Mail\BusinessForSaleMail;
use App\Mail\PayAttentionSystem;
use App\Mail\PayAttentionTo;
use App\Models\Logger;
use App\Models\MongoRequest;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Debug\Exception;

class SystemSubscriber implements ShouldQueue
{
    public $tries = 2;

    public function BusinessEmail(SystemEvent $event)
    {
        Mail::send(new BusinessForSaleMail($event->fileName,$event->email));
        Storage::disk('temp')->delete($event->fileName);
    }


    public function subscribe($events)
    {
        $events->listen(
            'App\Events\SystemEvent',
            'App\Listeners\SystemSubscriber@BusinessEmail'
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
