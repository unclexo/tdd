<?php

namespace Tests\Feature;

use App\Mail\OrderShipped;
use App\Mail\PostPublished;
use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
        $orderShipped = new OrderShipped();

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
}
