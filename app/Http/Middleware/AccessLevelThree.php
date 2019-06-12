<?php

namespace App\Http\Middleware;

use App\Exceptions\BaseException;
use App\Traits\Consts;
use Closure;
use Illuminate\Support\Facades\Auth;

class AccessLevelThree
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
        if($user->access_level == Consts::ACCOUNT_ACCESS_LEVEL_THREE){
            return $next($request);
        }
        throw new BaseException(Consts::ACCOUNT_ACCESS_DENY);
    }
}
