@extends('layouts.app')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.users.all') }}" class="btn btn-secondary">
        ← Back to Users
    </a>
</div>
<!-- Assuming $order contains the order data passed to the Blade view -->

<div style="color: white; padding: 20px;">
    <h2 style="border-bottom: 2px solid #555; padding-bottom: 10px;">User Details</h2>
    <img src="{{asset('storage/app/public/' . $user->picture)}}" alt="">
    <p><strong>User ID:</strong> {{ $user->id }}</p>
    <p><strong>User Name:</strong> {{ $user->first_name }} {{ $user->last_name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Phone:</strong> {{ $user->phone }}</p>
    <p><strong>Address:</strong> {{ $user->address }}</p>

</div>


{{-- <div style="color: white; padding: 20px;">
    <h3 style="border-bottom: 2px solid #555; padding-bottom: 10px;">Order Items</h3>
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr style="background-color: #333; color: white;">
                <th style="padding: 10px; text-align: left;">Product ID</th>
                <th style="padding: 10px; text-align: left;">Price</th>
                <th style="padding: 10px; text-align: left;">Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItem as $item)
                <tr style="border-bottom: 1px solid #555;">
                    <td style="padding: 10px;">{{ $item->product_id }}</td>
                    <td style="padding: 10px;">{{ $item->order_price }}</td>
                    <td style="padding: 10px;">{{ $item->order_quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div> --}}


@endsection