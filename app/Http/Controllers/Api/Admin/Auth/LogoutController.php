<?php

namespace App\Http\Controllers\Api\Admin\Auth;


use app\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->user("admin-api")->currentAccessToken()->delete();

        return ApiResponse::SendResponse(200, "Logged out successfully");
    }
}
