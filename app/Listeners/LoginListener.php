<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LoginListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return mixed
     */
    public function handle(Login $event)
    {
        // Do your stuff

        // logger("Logged in user: {$event->user->email}");

        // For testing purpose only, in this case.
        $this->doSomething();

        // For testing purpose only, in this case.
        return $event;
    }

    public function doSomething()
    {
    }
}
