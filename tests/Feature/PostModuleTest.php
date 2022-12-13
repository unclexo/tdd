<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostModuleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_create_a_post()
    {
        $this->actingAs($user = User::factory()->create());

        $this->post(route('posts.store'), $attributes = Post::factory()->raw(['user_id' => $user->id]));

        $this->assertDatabaseHas('posts', $attributes);
    }

    /** @test */
    public function users_can_view_their_own_posts()
    {
        $this->withoutExceptionHandling();

        $post = Post::factory()->create();

        $this->actingAs($post->user)
            ->get($post->path())
            ->assertSee($post->title)
            ->assertSee($post->description);
    }
}
