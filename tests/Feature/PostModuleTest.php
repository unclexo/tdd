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
        $this->withoutExceptionHandling();

        $this->actingAs(User::factory()->create());

        $this->post(route('posts.store'), $attributes = Post::factory()->raw());

        $this->assertDatabaseHas('posts', $attributes);
    }
}
