<?php

namespace App\Listeners;

use App\Events\ResetPassword;
use App\Jobs\SendResetPasswordLinks;
use App\Mail\ResetPassword as MailResetPassword;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendResetPasswordLink
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
    public function handle(ResetPassword $event): void
    {
        $job = new SendResetPasswordLinks($event->user, $event->token, $event->user_model);
        dispatch($job);
    }
}
