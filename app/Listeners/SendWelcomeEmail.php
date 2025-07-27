<?php

namespace App\Listeners;

use App\Events\UserWasRegistered;
use App\Jobs\SendWelcomeEmailJob;

class SendWelcomeEmail
{
    public function handle(UserWasRegistered $event): void
    {
        SendWelcomeEmailJob::dispatch($event->user);
    }
}