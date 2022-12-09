<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that guest cannot manage posts
     *
     * - Accessing to the form redirects to login page
     */
    public function test_guest_cannot_manage_posts()
    {
        $this->get(route('posts.create'))->assertRedirect(route('login'));
    }

    /**
     * Simple form test
     *
     * Test creating a post without validation
     *
     * - Log in as a user
     * - Make a post request with data created using factory
     * - Assert database has those data
     */
    public function test_a_user_can_create_a_post()
    {
        $this->actingAs(User::factory()->create());

        $this->post(
            route('posts.store.data.without.validation'),
            $attributes = Post::factory()->raw()
        );

        $this->assertDatabaseHas('posts', $attributes);
    }
}
