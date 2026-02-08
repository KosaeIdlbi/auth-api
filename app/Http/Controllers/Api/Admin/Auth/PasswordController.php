<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use App\Events\ResetPassword;
use app\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    public function sendResetLink(Request $request)
    {
        $validator = Validator::make(
            [
                'email' => $request->email,
            ],
            [
                'email' => ['required', 'string', 'email', 'max:255'],
            ]
        );
        if ($validator->fails()) {
            return ApiResponse::SendResponse(422, "validation error", $validator->errors());
        }

        $user = Admin::where("email", "=", $request->email)->first();
        if ($user == null) {
            return ApiResponse::SendResponse(200, "if your email correct you will get link on your email", []);
        } else {
            $token = Str::random(8);
            DB::table("password_reset_tokens")->insert([
                "email" => $request->email,
                "token" => $token,
                "created_at" => now(),
            ]);
            event(new ResetPassword($user, $token, "user"));
            return ApiResponse::SendResponse(200, "check your email we send you a reset link", []);
        }
    }
    public function update(Request $request)
    {
        $data = DB::table("password_reset_tokens")->where("token", "=", $request->token)->first();
        if ($data) {
            $created_at = Carbon::parse($data->created_at);
            $isNotExpire = ($created_at->diffInMinutes(now()) <= config("password.expire_time")) ? true : false;
            if ($isNotExpire) {
                $validator = Validator::make(
                    [
                        'password' => $request->password,
                        'password_confirmation' => $request->password,
                    ],
                    [
                        'password' => ['required', 'confirmed', Rules\Password::defaults()],
                    ]
                );
                if ($validator->fails()) {
                    return ApiResponse::SendResponse(422, "validation error", $validator->errors());
                }
                $email = $data->email;
                $user = Admin::where("email", "=", $email)->first();
                $user->update(["password" => Hash::make(trim($request->password))]);
                return ApiResponse::SendResponse(200, "your password updated", []);
            } else {
                return ApiResponse::SendResponse(200, "your code is incorrect or your reset password link expired", []);
            }
        } else {
            return ApiResponse::SendResponse(200, "your code is incorrect or your reset password link expired", []);
        }
    }
}
