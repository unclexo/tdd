<?php

namespace App\Listeners;

use App\Events\OrderUpdatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrderUpdateListener
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
     * @param  \App\Events\OrderUpdatedEvent  $event
     * @return void
     */
    public function handle(OrderUpdatedEvent $event)
    {
        //
    }
}
