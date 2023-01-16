<?php

namespace App\Providers;

use App\Events\OrderCreatedEvent;
use App\Events\OrderDeletedEvent;
use App\Events\OrderUpdatedEvent;
use App\Listeners\LoginListener;
use App\Listeners\LogoutListener;
use App\Listeners\OrderCreationListener;
use App\Listeners\OrderDeletionListener;
use App\Listeners\OrderUpdateListener;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Login::class => [
            LoginListener::class,
        ],
        Logout::class => [
            LogoutListener::class,
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderCreatedEvent::class => [
            OrderCreationListener::class,
        ],
        OrderUpdatedEvent::class => [
            OrderUpdateListener::class,
        ],
        OrderDeletedEvent::class => [
            OrderDeletionListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
