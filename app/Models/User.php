<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        "email_verified_at",
        "email_verification_token",
        "email_verification_token_expires_at",
        "timezone",
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        "created_at" => 'datetime',
        "updated_at" => 'datetime',
        "email_verification_token_expires_at" => 'datetime',
        'password' => 'hashed',
    ];

    public function getEmailVerifiedAtAttribute($value)
    {
        // .بقاعدة البيانات الوقت null واخذنا منه carbon:parse لكن ال carbon object يعطي true
        // لذلك اذا كان هناك حقل نفحص قيمته اذا كانت null ام لا لانحوله الى carbon object الا عندما نتاكد انه ليس null
        if ($value) {
            $EmailVerifiedAt = Carbon::parse($value)->setTimezone($this->timezone);
            return $EmailVerifiedAt;
        }
    }
    public function getCreatedAtAttribute($value)
    {
        $CreatedAt = Carbon::parse($value)->setTimezone($this->timezone);
        return $CreatedAt;
    }
    public function getUpdatedAtAttribute($value)
    {
        $UpdatedAt = Carbon::parse($value)->setTimezone($this->timezone);
        return $UpdatedAt;
    }
    public function getEmailVerificationTokenExpiresAtAttribute($value)
    {
        $EmailVerificationTokenExpiresAt = Carbon::parse($value)->setTimezone($this->timezone);
        return $EmailVerificationTokenExpiresAt;
    }
}
