<?php

use app\Helpers\ApiResponse;
use App\Http\Controllers\Api\Admin\Auth\LoginController;
use App\Http\Controllers\Api\Admin\Auth\LogoutController;
use App\Http\Controllers\Api\Admin\Auth\PasswordController;
use App\Http\Controllers\api\Admin\Auth\RegisterController;
use App\Http\Controllers\Api\Admin\Auth\VerifyController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//guest routes
Route::prefix("admin/")->as("admin.")->group(function () {
    Route::post("register", RegisterController::class)->name("register");
    Route::post("login", LoginController::class)->name("login");
    Route::post("reset-password", [PasswordController::class, "sendResetLink"])->name("password.sendResetLink")->middleware("throttle:admin-password-reset-links");
    Route::put("reset-password", [PasswordController::class, "update"])->name("password.update");
});

//auth but not verified routes
Route::middleware(["auth:admin-api", "NotVerify:admin-api"])->prefix("admin/verify/")->as("admin.verify.")->group(function () {
    Route::post("", [VerifyController::class, "verifyuser"])->name("verifyuser"); //from gmail
    Route::put("", [VerifyController::class, "update"])->name("update")->middleware("throttle:admin-verification-links");
});

//auth routes
Route::middleware(["auth:admin-api"])->prefix("admin/")->as("admin.")->group(function () {
    Route::post("logout", LogoutController::class)->name("logout");
});

//auth and verified routes
Route::middleware(["auth:admin-api", "Verify:admin-api"])->prefix("admin/")->as("admin.")->group(function () {
    Route::get("home", function () {
        $admin = Auth::guard("admin-api")->user();
        return ApiResponse::SendResponse(200, "welcome home", $admin);
    });
});
