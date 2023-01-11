<?php

namespace App\Http\Controllers;

use App\Events\OrderCreatedEvent;

class OrderController extends Controller
{
    public function store()
    {
        $attributes = \request()->validate([
            'shipper_id' => ['required'],
            'name' => ['required'],
            'price' => ['required'],
        ]);

        $order = auth()->user()->orders()->create($attributes);

        OrderCreatedEvent::dispatch($order);
    }
}
