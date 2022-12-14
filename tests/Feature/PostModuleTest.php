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
    public function a_user_can_create_a_post_2()
    {
        $this->actingAs($user = User::factory()->create());

        $this->get(route('posts.create'))->assertStatus(200);

        $this->followingRedirects()
            ->post(route('posts.store'), $attributes = Post::factory()->raw(['user_id' => $user->id]))
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description']);
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

    /** @test */
    public function a_post_requires_valid_data()
    {
        $this->actingAs(User::factory()->create());

        $attributes = Post::factory()->raw([
            'title' => '',
            'description' => '',
        ]);

        $this->post(route('posts.store'), $attributes)
            ->assertSessionHasErrors(['title', 'description']);


        $attributes2 = Post::factory()->raw([
            'title' => 12345,
            'description' => 'hi',
        ]);

        $this->post(route('posts.store'), $attributes2)
            ->assertSessionHasErrors(['title', 'description']);
    }

    /** @test */
    public function a_user_can_update_a_post()
    {
        $this->actingAs($user = User::factory()->create());

        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->patch(
            $post->path(),
            $attributes = ['title' => 'updated']
        )->assertRedirect($post->path());

        $this->assertDatabaseHas('posts', $attributes);
    }

    /** @test */
    public function a_user_can_delete_a_post()
    {
        $this->actingAs($user = User::factory()->create());

        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->delete($post->path())->assertRedirect(route('posts.index'));

        $this->assertDatabaseMissing('posts', $post->only('id'));
    }
}
