<?php

namespace Tests\Feature;

use App\Mail\OrderShipped;
use App\Mail\PostPublished;
use App\Models\Order;
use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Mail\Mailables\Attachment;
use Tests\TestCase;

class MailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function mailables_are_available()
    {
        $this->actingAs($user = User::factory()->create());

        $post = Post::factory()->create(['user_id' => $user->id]);
        $postPublished = new PostPublished($post);

        $order = Order::factory()->create(['user_id' => $user->id]);
        $orderShipped = new OrderShipped($order);

        $this->assertInstanceOf(Mailable::class, $postPublished);
        $this->assertInstanceOf(Mailable::class, $orderShipped);
    }

    /** @test */
    public function mailable_can_be_previewed()
    {
        $this->actingAs($user = User::factory()->create());

        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->get(route('mailable.preview', $post->id))
            ->assertStatus(200);
    }

    /** @test */
    public function mailable_has_valid_content()
    {
        $this->actingAs($user = User::factory()->create());

        $post = Post::factory()->create(['user_id' => $user->id]);

        $mailable = new PostPublished($post);

        $mailable->assertFrom('medium@example.com');

        $mailable->assertTo('unclexo@example.com');

        $mailable->assertHasBcc('ria@example.com');

        $mailable->assertHasReplyTo('taylor@example.com');

        $mailable->assertHasSubject('Post Published');

        $mailable->assertHasTag('design-patterns');

        $mailable->assertHasMetadata('post_id', $post->id);

        $mailable->assertSeeInHtml('Post Published');

        $mailable->assertSeeInOrderInHtml(['View Post', 'Thanks']);

        $mailable->assertSeeInOrderInText(['View Post', 'Thanks']);
    }

    /** @test */
    public function content_can_be_set_to_mailable_at_runtime()
    {
        $this->actingAs($user = User::factory()->create());

        $order = Order::factory()->create(['user_id' => $user->id]);

        $mailable = new OrderShipped($order);

        $mailable
            ->from('amazon@exmaple.com', 'Amazon Books')
            ->to('unclexo@example.com', 'Abu Jobaer')
            ->bcc('ria@example.com', 'Ria Jobaer')
            ->replyTo('taylor@example.com', 'Taylor Otwell')
            ->subject('Order Shipped')
            ->tag('shipment')
            ->tag('fast-delivery')
            ->metadata('order_id', $order->id);


        $mailable->assertFrom('amazon@exmaple.com');

        $mailable->assertTo('unclexo@example.com');

        $mailable->assertHasBcc('ria@example.com');

        $mailable->assertHasReplyTo('taylor@example.com');

        $mailable->assertHasSubject('Order Shipped');

        $mailable->assertHasTag('shipment');

        $mailable->assertHasMetadata('order_id', $order->id);

        $mailable->assertSeeInHtml('Order Shipped');
    }

    /** @test */
    public function mailable_can_have_attachments()
    {
        $this->actingAs($user = User::factory()->create());

        $order = Order::factory()->create(['user_id' => $user->id]);

        $mailable = new OrderShipped($order);

        $mailable->assertHasAttachment(Attachment::fromPath(storage_path('app/public/some.pdf')));
        $mailable->assertHasAttachment(Attachment::fromStorageDisk('public', 'other.pdf'));
    }

    /** @test */
    public function mailable_can_have_attachments_at_runtime()
    {
        $this->actingAs($user = User::factory()->create());

        $order = Order::factory()->create(['user_id' => $user->id]);

        $mailable = new OrderShipped($order);

        $mailable->attachFromStorageDisk('public', 'your-order.pdf');

        $mailable->assertHasAttachmentFromStorageDisk('public', 'your-order.pdf');
    }
}
