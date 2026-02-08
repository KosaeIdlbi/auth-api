<?php

namespace App\Http\Middleware;

use app\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class NotVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $guard): Response
    {
        if ($guard == "admin-api") {
            $user = Auth::guard($guard)->user();
            if (!$user->email_verified_at) {
                return $next($request);
            } else {
                return ApiResponse::SendResponse(200, "you are verified");
            }
        } elseif ($guard == "user-api") {
            $user = Auth::guard($guard)->user();
            if (!$user->email_verified_at) {
                return $next($request);
            } else {
                return ApiResponse::SendResponse(200, "you are verified");
            }
        }
        return $next($request);
    }
}
