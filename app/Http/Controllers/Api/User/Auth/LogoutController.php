<?php

namespace App\Http\Controllers\Api\User\Auth;

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
        $request->user()->currentAccessToken()->delete();

        return ApiResponse::SendResponse(200, "Logged out successfully");
    }
}
