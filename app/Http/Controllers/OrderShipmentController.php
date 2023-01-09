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

    public function shipOrderAdvanced(Order $order)
    {

        $order->status = 'shipped';
        $order->save();

        $mailable = (new OrderShipped($order))
            ->onConnection('redis')
            ->onQueue('order_shipment');

        $user = auth()->user();

        Mail::to($user)
            ->cc($user->ccEmails())
            ->bcc($user->bccEmails())
            ->queue($mailable);

        return ['message' => 'Your order has been shipped.'];
    }
}
