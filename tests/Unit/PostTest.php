<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_post_has_path()
    {
        $post = Post::factory()->create();

        $this->assertEquals(route('posts.show', $post->id), $post->path());
    }

    /** @test */
    public function a_post_has_an_owner()
    {
        $post = Post::factory()->create();

        $this->assertInstanceOf(User::class, $post->user);
    }
}
