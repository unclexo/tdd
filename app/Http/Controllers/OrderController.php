<?php

namespace App\Http\Controllers;

use App\Events\OrderCreatedEvent;
use App\Events\OrderUpdatedEvent;
use App\Models\Order;
use Illuminate\Validation\Rule;

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

    public function update(Order $order)
    {
        $attributes = \request()->validate([
            'shipper_id' => ['sometimes', 'numeric'],
            'name' => ['required'],
            'price' => ['sometimes'],
            'status' => ['sometimes', Rule::in(['pending', 'processing', 'shipped', 'cancelled', 'completed'])],
        ]);

        $order->update($attributes);

        OrderUpdatedEvent::dispatch($order);
    }
}
