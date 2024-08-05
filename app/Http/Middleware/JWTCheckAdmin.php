<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
class JWTCheckAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if($request['user']->role != 'admin') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
