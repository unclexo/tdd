<x-mail::message>
# Order Shipped

Your order has been shipped. The order summary:

    Order ID: {{ $order->id }}
    Name: {{ $order->name }}
    Price: {{ $order->price }}

<x-mail::button :url="$url">
View Order
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
