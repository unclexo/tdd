<?php

namespace App\Listeners;

use App\Events\OrderDeletedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrderDeletionListener
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
     * @param  \App\Events\OrderDeletedEvent  $event
     * @return void
     */
    public function handle(OrderDeletedEvent $event)
    {
        //
    }
}
