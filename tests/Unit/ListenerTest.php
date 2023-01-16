<?php

namespace Tests\Unit;


use App\Listeners\LoginListener;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListenerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function handle_method_of_an_event_listener()
    {
        $user = User::factory()->create();

        $login = new Login('web', $user, false);

        $listener = $this->mock(LoginListener::class)->makePartial();

        $listener->shouldReceive('doSomething')
            ->once()
            ->andReturnNull();

        // Say, the handle() method is being called by the event API
        $loginEvent = $listener->handle($login);

        $this->assertSame('web', $loginEvent->guard);

        $this->assertFalse($loginEvent->remember);

        $this->assertSame($user->email, $loginEvent->user->email);
    }
}
