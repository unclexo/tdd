<?php

namespace Tests\Feature;

use App\Events\OrderCreatedEvent;
use App\Events\OrderDeletedEvent;
use App\Events\OrderUpdatedEvent;
use App\Listeners\OrderCreationListener;
use App\Listeners\OrderDeletionListener;
use App\Listeners\OrderUpdateListener;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

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

    /** @test */
    public function triggering_an_event_after_creating_orders()
    {
        $this->actingAs($user = User::factory()->create());

        Event::fake();

        Event::assertNotDispatched(OrderCreatedEvent::class);

        $this->post(route('orders.store'), [
            'shipper_id' => 4,
            'name' => 'test order',
            'price' => 345,
        ]);

        Event::assertDispatched(OrderCreatedEvent::class);
    }

    /** @test */
    public function triggering_an_event_after_updating_orders()
    {
        $this->actingAs($user = User::factory()->create());

        Event::fake();

        Event::assertNotDispatched(OrderUpdatedEvent::class);

        $this->patch(route('orders.update', Order::factory()->create()), [
            'name' => 'changed',
            'status' => 'shipped',
        ]);

        Event::assertDispatched(OrderUpdatedEvent::class);
    }

    /** @test */
    public function triggering_an_event_after_deleting_orders()
    {
        $this->actingAs($user = User::factory()->create());

        Event::fake();

        Event::assertNotDispatched(OrderDeletedEvent::class);

        $this->delete(route('orders.delete', Order::factory()->create()));

        Event::assertDispatched(OrderDeletedEvent::class);
    }
}
