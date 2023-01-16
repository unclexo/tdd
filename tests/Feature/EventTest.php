<?php

namespace Tests\Feature;

use App\Events\OrderCreatedEvent;
use App\Events\OrderDeletedEvent;
use App\Events\OrderUpdatedEvent;
use App\Listeners\LoginListener;
use App\Listeners\LogoutListener;
use App\Listeners\OrderCreationListener;
use App\Listeners\OrderDeletionListener;
use App\Listeners\OrderUpdateListener;
use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
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

    /** @test */
    public function listening_to_builtin_events()
    {
        Event::fake();

        Event::assertListening(Login::class, LoginListener::class);

        Event::assertListening(Logout::class, LogoutListener::class);
    }

    /** @test */
    public function subscribing_to_login_event()
    {
        $user = User::factory()->create();

        Event::fake();

        Event::assertNotDispatched(Login::class);

        // Login event triggers when a user logs in
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        Event::assertDispatched(Login::class, function($event) use($user) {
            return $event->user->email === $user->email;
        });
    }

    /** @test */
    public function subscribing_to_logout_event()
    {
        $this->actingAs($user = User::factory()->create());

        Event::fake();

        Event::assertNotDispatched(Logout::class);

        // Logout event triggers when a user logs out
        $this->post(route('logout'));

        Event::assertDispatched(Logout::class, function($event) use($user) {
            return $event->user->email === $user->email;
        });
    }
}
