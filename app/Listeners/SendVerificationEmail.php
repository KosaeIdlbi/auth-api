<?php

namespace App\Listeners;

use App\Events\VerificationRequire;
use App\Jobs\SendVerificationsLinks;
use App\Mail\Verification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendVerificationEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(VerificationRequire $event): void
    {
        $job = new SendVerificationsLinks($event->user, $event->token, $event->user_model);
        dispatch($job);
    }
}
