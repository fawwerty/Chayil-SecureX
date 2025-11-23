<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Events\IncidentReported;
use App\Events\MessageSent;
use App\Listeners\SendIncidentNotification;
use App\Listeners\SendMessageNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        IncidentReported::class => [
            SendIncidentNotification::class,
        ],
        MessageSent::class => [
            SendMessageNotification::class,
        ],
        \App\Events\IncidentReported::class => [
            \App\Listeners\SendIncidentNotification::class,
        ],
        \App\Events\MessageSent::class => [
            \App\Listeners\SendMessageNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}

