<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\UserWasRegistered;
use App\Listeners\SendWelcomeEmail;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     */
    protected $listen = [
        UserWasRegistered::class => [
            SendWelcomeEmail::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}