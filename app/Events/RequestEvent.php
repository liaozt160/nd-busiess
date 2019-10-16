<?php

namespace App\Events;

use App\Models\MongoRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RequestEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $logger;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $logger['created_at'] = defined("LARAVEL_START")?date('Y-m-d H:i:s',constant("LARAVEL_START")):date('Y-m-d H:i:s');
        $logger['url'] = $request->path();
        $logger['user_id'] = Auth::id();
        $logger['method'] = $request->getMethod();
        $logger['is_ajax'] = $request->ajax();
        $logger['header'] = $request->header();
        $logger['x-forwarded-for'] = $request->header('x-forwarded-for');
        $logger['x-real-ip'] = $request->header('x-real-ip');
        $logger['cookies'] = $request->cookie();
        $logger['query'] = $request->query();
        $logger['post'] = $request->post();
        $logger['json'] = $request->json();
        $logger['client_ip'] = $request->getClientIp();
        $logger['client_ips'] = $request->getClientIps();
        $logger['agent'] = $request->userAgent();
        $logger['host'] = $request->getHost();
        $logger['scheme'] = $request->getScheme();
        $this->logger = $logger;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
//        return new PrivateChannel('channel-name');
    }
}
