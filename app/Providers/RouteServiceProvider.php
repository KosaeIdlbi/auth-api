<?php

namespace App\Providers;

use app\Helpers\ApiResponse;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //middleware("throttle:user-verification-links")

        RateLimiter::for('user-verification-links', function () {
            return Limit::perMinutes(30, 2)->by(Auth::guard("user-api")->id())
                ->response(function () {
                    $message = "you can use this request 2 times in 30 minutes";
                    return ApiResponse::SendResponse("200", $message);
                });
        });
        RateLimiter::for('user-password-reset-links', function ($request) {
            return Limit::perMinutes(30, 2)->by($request->email)
                ->response(function () use ($request) {
                    $message = "you can use this request 2 times in 30 minutes";
                    return ApiResponse::SendResponse("200", $message, ["email" => $request->email]);
                });
        });
        //================================================================================================
        RateLimiter::for('admin-verification-links', function () {
            return Limit::perMinutes(30, 2)->by(Auth::guard("admin-api")->id())
                ->response(function () {
                    $message = "you can use this request 2 times in 30 minutes";
                    return ApiResponse::SendResponse("200", $message);
                });
        });
        RateLimiter::for('admin-password-reset-links', function ($request) {
            return Limit::perMinutes(30, 2)->by($request->email)
                ->response(function () use ($request) {
                    $message = "you can use this request 2 times in 30 minutes";
                    return ApiResponse::SendResponse("200", $message, ["email" => $request->email]);
                });
        });
    }
}
