<?php

namespace Tests\Feature;


use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderShipmentNotification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function notifying_users_after_shipping_orders()
    {
        $this->actingAs($user = User::factory()->create());

        Notification::fake();

        $this->patch(route('order.shipment.notification', $order = Order::factory()->create()));

        Notification::assertSentTo($user, OrderShipmentNotification::class, function ($notification) use ($user, $order) {
            return $notification->order->id === $order->id &&
                in_array('mail', $notification->via($user)) &&
                ($notification->toMail($user) instanceof MailMessage);
        });
    }

    /** @test */
    public function notifying_the_user_while_requesting_reset_password_link()
    {
        $this->get(route('password.request'))->assertStatus(200);

        Notification::fake();

        $user = User::factory()->create();

        $this->post(route('password.email'), ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class);
    }
}