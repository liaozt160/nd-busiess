<?php

namespace App\Listeners;

use App\Events\ContactUsEvent;
use App\Mail\ContactManageNotice;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class ContactUsListener implements ShouldQueue
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
     * @param  ContactUsEvent  $event
     * @return void
     */
    public function handle(ContactUsEvent $event)
    {
        Mail::send(new ContactManageNotice($event->contact));
    }
}
