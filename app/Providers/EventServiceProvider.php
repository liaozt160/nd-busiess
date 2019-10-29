<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\PayAttention' => [
//            'App\Listeners\PayAttentionListener',
        ],
        'App\Events\ContactUsEvent' => [
            'App\Listeners\ContactUsListener',
        ],
        'App\Events\BusinessEmailEvent' => [
            'App\Listeners\BusinessEmailListener',
        ],
        'App\Events\SystemEmailEvent' => [
            'App\Listeners\BusinessEmailListener',
        ],
    ];

    protected $subscribe = [
        'App\Listeners\PayAttentionSubscriber',
        'App\Listeners\RequestSubscriber',
        'App\Listeners\SystemSubscriber',
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
