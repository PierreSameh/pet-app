@extends('layouts.app')

@section('content')
@if (count($orders) > 0)
    <!-- ShowStores.blade.php -->
<table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
    <thead>
        <tr style="background-color: #333; color: #fff;">
            <th style="padding: 12px 15px; text-align: left;">Order ID</th>
            <th style="padding: 12px 15px; text-align: left;">Ordered By</th>
            <th style="padding: 12px 15px; text-align: left;">Subtotal</th>
            <th style="padding: 12px 15px; text-align: left;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr style="border-bottom: 1px solid #ddd;">
            <td style="padding: 12px 15px;">{{ $order->id }}</td>
            <td style="padding: 12px 15px;">{{ $order->user->first_name . " " . $order->user->last_name }}</td>
            <td style="padding: 12px 15px;">{{ $order->subtotal }}</td>
            <td style="padding: 12px 15px;">
                <a href="{{route('admin.get.order', $order->id)}}" style="text-decoration: none; color: #fff; background-color: #007bff; padding: 5px 10px; border-radius: 5px;">Order Details</a>
                {{-- <form action="" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" onclick="return confirm('are you sure?')"
                    style="text-decoration: none; color: #fff; background-color: #dc3545; padding: 5px 10px; border-radius: 5px; border: none; cursor: pointer;">Delete</button>
                </form> --}}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<h1>There is no Orders Yet</h1>
@endif
    
@endsection