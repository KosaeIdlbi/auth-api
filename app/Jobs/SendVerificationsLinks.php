<?php

namespace App\Jobs;

use App\Mail\Verification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendVerificationsLinks implements ShouldQueue
{
    use Queueable;

    public $user;
    public $token;
    public $user_model;
    public function __construct($user, $token, $user_model)
    {
        $this->user = $user;
        $this->token = $token;
        $this->user_model = $user_model;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user->email)->send(new Verification($this->user, $this->token, $this->user_model));
    }
}
