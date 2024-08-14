@extends('layouts.app')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.books.clinic') }}" class="btn btn-secondary">
        ← Back to Booked Visits
    </a>
</div>
<!-- Assuming $order contains the order data passed to the Blade view -->

<div style="color: white; padding: 20px;">
    <h2 style="border-bottom: 2px solid #555; padding-bottom: 10px;">Book Details</h2>

    <p><strong>Book ID:</strong> {{ $book->id }}</p>
    <p><strong>Booked Date:</strong> {{ $book->time }}</p>
    <p><strong>User Name:</strong> {{ $book->user->first_name }} {{ $book->user->last_name }}</p>
    <p><strong>Email:</strong> {{ $book->user->email }}</p>
    <p><strong>Phone:</strong> {{ $book->user->phone }}</p>
    <p><strong>Address:</strong> {{ $book->user->address }}</p>

</div>
<div style="color: white; padding: 20px;">
    <h2 style="border-bottom: 2px solid #555; padding-bottom: 10px;">Clinic Details</h2>

    <p><strong>Clinic ID:</strong> {{ $book->clinic->id }}</p>
    <p><strong>Clinic Name:</strong> {{ $book->clinic->clinic_name }}</p>
    <p><strong>Doctor:</strong> {{ $book->clinic->doctor}}</p>
    <p><strong>Specialization:</strong> {{ $book->clinic->specialization }}</p>
    <p><strong>Address:</strong> {{ $book->clinic->address }}</p>
    <p><strong>Medical Fees:</strong> {{ $book->clinic->medical_fees }}</p>
    <p><strong>Working Days:</strong> {{ $book->clinic->working_days }}</p>
    <p><strong>Working Times:</strong> {{ $book->clinic->working_times }}</p>

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