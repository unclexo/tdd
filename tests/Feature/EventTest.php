<?php

namespace Tests\Feature;

use App\Events\OrderCreatedEvent;
use App\Events\OrderDeletedEvent;
use App\Events\OrderUpdatedEvent;
use App\Listeners\OrderCreationListener;
use App\Listeners\OrderDeletionListener;
use App\Listeners\OrderUpdateListener;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class EventTest extends TestCase
{
    /** @test */
    public function event_listeners_are_listening_to_given_events()
    {
        Event::fake();

        Event::assertListening(
            OrderCreatedEvent::class,
            OrderCreationListener::class
        );

        Event::assertListening(
            OrderUpdatedEvent::class,
            OrderUpdateListener::class
        );

        Event::assertListening(
            OrderDeletedEvent::class,
            OrderDeletionListener::class
        );
    }
}
