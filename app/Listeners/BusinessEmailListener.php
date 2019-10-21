<?php

namespace App\Listeners;

use App\Events\BusinessEmailEvent;
use App\Mail\RecommendBusiness;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class BusinessEmailListener implements ShouldQueue
{
    public $tries = 2;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BusinessEmailEvent  $event
     * @return void
     */
    public function handle(BusinessEmailEvent $event)
    {
        $fileName = $event->fileName;
        $email = $event->email;
        Mail::send(new RecommendBusiness($fileName,$email));
        Storage::disk('temp')->delete($fileName);
    }
}
