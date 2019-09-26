<?php

namespace App\Listeners;

use App\Events\PayAttention;
use App\Mail\PayAttentionSystem;
use App\Mail\PayAttentionTo;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PayAttentionSubscriber implements ShouldQueue
{

    public function payAttention(PayAttention $payAttention)
    {

        $business = $payAttention->businessAttention->business;
        if($business && $business->account){

            try{
                Mail::send(new PayAttentionTo($business,$business->account->email));
            }catch (\Exception $e){
                Log::info($e->getMessage());
            }
        }
        $account = $payAttention->businessAttention->account;
        if($account){
            try{
                Mail::send(new PayAttentionSystem($business,$account->email));
            }catch (\Exception $e){
                Log::info($e->getMessage());
            }
        }
    }

    public function payAttentionTo(PayAttention $payAttention)
    {
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\PayAttention',
            'App\Listeners\PayAttentionSubscriber@payAttention'
        );

        $events->listen(
            'App\Events\PayAttention',
                'App\Listeners\PayAttentionSubscriber@payAttentionTo'
        );
    }
}
