<?php

namespace App\Listeners;

use App\Events\PayAttention;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PayAttentionListener implements ShouldQueue
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
     * @param  PayAttention  $event
     * @return void
     */
    public function handle(PayAttention $event)
    {

    }
}
