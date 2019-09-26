<?php

namespace App\Events;

use App\Models\Contact;
use App\Models\Logger;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Auth;

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
        $logger['url'] = $request->path();
        $logger['user_id'] = Auth::id();
        $logger['method'] = $request->getMethod();
        $logger['is_ajax'] = $request->ajax();
        $logger['header'] = $request->header();
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
