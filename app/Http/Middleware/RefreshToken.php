<?php

namespace App\Http\Middleware;

use App\Exceptions\BaseException;
use App\Traits\Consts;
use Closure;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class RefreshToken extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $this->authenticate($request);
        } catch (\Exception $exception) {
            throw new BaseException(Consts::TOKEN_WRONG, $exception->getMessage());
        }
        return $next($request);
    }
}
