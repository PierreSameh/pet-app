@extends('layouts.app')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.support.messages') }}" class="btn btn-secondary">
        ← Back to Messages
    </a>
</div>
<!-- Assuming $order contains the order data passed to the Blade view -->

<div style="color: white; padding: 20px;">

    <h2 style="border-bottom: 2px solid #555; padding-bottom: 10px;">Message Details</h2>
    <p><strong>Message ID:</strong> {{ $message->id }}</p>
    <p><strong>User ID:</strong> {{ $message->user_id }}</p>
    <p><strong>User Name:</strong> {{ $message->user->first_name }} {{ $message->user->last_name }}</p>
    <p><strong>Email:</strong> {{ $message->user->email }}</p>
    <p><strong>Phone:</strong> {{ $message->user->phone }}</p>
    <p><strong>Message:</strong> {{ $message->message }}</p>
    <form action="{{ route('admin.support.delete', $message->id) }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" onclick="return confirm('are you sure?')"
        style="text-decoration: none; color: #fff; background-color: #dc3545; padding: 5px 10px; border-radius: 5px; border: none; cursor: pointer;">Delete</button>
    </form>
    

</div>

@endsection