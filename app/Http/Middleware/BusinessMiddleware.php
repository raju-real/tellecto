<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BusinessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
//        if (auth()->guard('business')->check()) {
//            return $next($request);
//        }
        if (isBusiness()) {
            return $next($request);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'You have no permission to access this activity!'
        ]);
    }
}
