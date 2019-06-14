<?php

namespace App\Http\Middleware;

use Closure;
use App\Exceptions\BaseException;
use App\Traits\Consts;
use Illuminate\Support\Facades\Auth;

class BusinessBroker
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
        $user = Auth::user();
        if($user->role == Consts::ACCOUNT_ROLE_BUSINESS_BROKER || $user->role == Consts::ACCOUNT_ROLE_ADMIN){
            return $next($request);
        }
        throw new BaseException(Consts::ACCOUNT_ACCESS_DENY);
    }
}
