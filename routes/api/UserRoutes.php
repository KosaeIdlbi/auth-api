<?php

use app\Helpers\ApiResponse;
use App\Http\Controllers\Api\User\Auth\LoginController;
use App\Http\Controllers\Api\User\Auth\LogoutController;
use App\Http\Controllers\Api\User\Auth\PasswordController;
use App\Http\Controllers\api\User\Auth\RegisterController;
use App\Http\Controllers\Api\User\Auth\VerifyController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//guest routes
Route::prefix("user/")->as("user.")->group(function () {
    Route::post("register", RegisterController::class)->name("register");
    Route::post("login", LoginController::class)->name("login");
    Route::post("reset-password", [PasswordController::class, "sendResetLink"])->name("password.sendResetLink")->middleware("throttle:user-password-reset-links");
    Route::put("reset-password", [PasswordController::class, "update"])->name("password.update");
});

//auth but not verified routes
Route::middleware(["auth", "NotVerify"])->prefix("user/verify/")->as("user.verify.")->group(function () {
    Route::post("", [VerifyController::class, "verifyUser"])->name("verifyUser"); //from gmail
    Route::put("", [VerifyController::class, "update"])->name("update")->middleware("throttle:user-verification-links");
});

//auth routes
Route::middleware(["auth"])->prefix("user/")->as("user.")->group(function () {
    Route::post("logout", LogoutController::class)->name("logout");
});

//auth and verified routes
Route::middleware(["auth", "Verify"])->prefix("user/")->as("user.")->group(function () {
    Route::get("home", function () {
        $user = Auth::user();
        return ApiResponse::SendResponse(200, "welcome home", $user);
    });
});
