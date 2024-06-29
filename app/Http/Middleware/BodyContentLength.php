<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BodyContentLength
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $maxContentLength = 1024 * 1024; // 1MB

        if ($request->header('Content-Length') > $maxContentLength) {
            return response()->json(['error' => 'Request payload too large'], 413);
        }

        return $next($request);
    }
}
