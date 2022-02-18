<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtRefresh
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     *
     * @throws TokenInvalidException
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!app('tymon.jwt.parser')->hasToken()) {
            throw new TokenInvalidException('Token invalid.', 401);
        }

        return $next($request);
    }
}
