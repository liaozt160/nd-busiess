<?php

namespace App\Http\Middleware;

use App\Events\RequestEvent;
use Closure;
use Illuminate\Support\Facades\App;

class CheckLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        event(new RequestEvent($request));
        $lang = $request->header('Language');
        if($lang){
            App::setLocale($lang);
        }
        return $next($request);
    }
}
