@extends('layouts.app')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.get.orders') }}" class="btn btn-secondary">
        ← Back to Orders
    </a>
</div>
<!-- Assuming $order contains the order data passed to the Blade view -->

<div style="color: white; padding: 20px;">
    <h2 style="border-bottom: 2px solid #555; padding-bottom: 10px;">Order Details</h2>

    <p><strong>Order ID:</strong> {{ $order->id }}</p>
    <p><strong>User Name:</strong> {{ $order->user->first_name }} {{ $order->user->last_name }}</p>
    <p><strong>Email:</strong> {{ $order->user->email }}</p>
    <p><strong>Phone:</strong> {{ $order->user->phone }}</p>
    <p><strong>Address:</strong> {{ $order->user->address }}</p>

    <p><strong>Subtotal:</strong> {{ $order->subtotal }}</p>
    <p><strong>Status:</strong> {{ $order->status }}</p>
    <p><strong>Payment Number:</strong> {{ $order->phone }}</p>
    <p><strong>Receipt:</strong><br><img src="{{ asset('storage/' . $order->receipt) }}" style="height: 400px;" /></p>
</div>

<div style="color: white; padding: 20px;">
    <h3 style="border-bottom: 2px solid #555; padding-bottom: 10px;">Order Items</h3>
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr style="background-color: #333; color: white;">
                <th style="padding: 10px; text-align: left;">Product ID</th>
                <th style="padding: 10px; text-align: left;">Product Name</th>
                <th style="padding: 10px; text-align: left;">Product Price</th>
                <th style="padding: 10px; text-align: left;">Sale Price</th>
                <th style="padding: 10px; text-align: left;">Order Price</th>
                <th style="padding: 10px; text-align: left;">Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItem as $item)
                <tr style="border-bottom: 1px solid #555;">
                    <td style="padding: 10px;">{{ $item->product_id }}</td>
                    <td style="padding: 10px;">{{ $item->product->name }}</td>
                    <td style="padding: 10px;">{{ $item->product->price }}</td>
                    <td style="padding: 10px;">{{ $item->product->offer == 1 ? $item->product->sale_amount : '' }}</td>
                    <td style="padding: 10px;">{{ $item->order_price }}</td>
                    <td style="padding: 10px;">{{ $item->order_quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


@endsection