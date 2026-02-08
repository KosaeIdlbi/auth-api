<?php

namespace App\Http\Controllers\Api\User\Auth;

use App\Events\VerificationRequire;
use app\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */

    public function __invoke(Request $request)
    {

        $validator = Validator::make(
            [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'password_confirmation' => $request->password_confirmation,
            ],
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]
        );

        if ($validator->fails()) {
            return ApiResponse::SendResponse(422, "validation error", $validator->errors());
        }

        $token = Str::random(8);
        $expireAt = now()->addMinute((int)config("verification.expire_time"));
        $user = User::create([
            "name" => trim($request->name),
            "email" => trim($request->email),
            "password" => Hash::make(trim($request->password)),
            "email_verification_token" => $token,
            "email_verification_token_expires_at" => $expireAt,
        ]);


        event(new VerificationRequire($user, $token, "user"));
        //حدث لارسال رابط التوثيق عبر الايميل
        $token = $user->createToken('api-register')->plainTextToken;
        $data = [
            "token" => $token,
            "name" => $user->name,
            "email" => $user->email,
        ];
        return ApiResponse::SendResponse(201, "Registration was successful. You now need to verify your account.", $data);
    }
}
