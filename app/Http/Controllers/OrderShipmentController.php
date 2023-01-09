<?php

namespace App\Http\Controllers;

use App\Mail\OrderShipped;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class OrderShipmentController extends Controller
{
    public function shipOrderBasic(Order $order)
    {
        $order->status = 'shipped';
        $order->save();

        Mail::to(auth()->user())->send(new OrderShipped($order));

        return ['message' => 'Your order has been shipped.'];
    }
}
