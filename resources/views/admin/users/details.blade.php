@extends('layouts.app')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.users.all') }}" class="btn btn-secondary">
        ← Back to Users
    </a>
</div>
<!-- Assuming $order contains the order data passed to the Blade view -->

<div style="color: white; padding: 20px;">
    <div style="flex-shrink: 0; margin-right: 20px;">
        <img src="{{asset('storage/app/public/' . $user->picture)}}" 
             alt="Profile Picture" 
             style="border-radius: 50%; width: 150px; height: 150px; object-fit: cover;">
    </div>

    <h2 style="border-bottom: 2px solid #555; padding-bottom: 10px;">User Details</h2>
    <p><strong>User ID:</strong> {{ $user->id }}</p>
    <p><strong>User Name:</strong> {{ $user->first_name }} {{ $user->last_name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Phone:</strong> {{ $user->phone }}</p>
    <p><strong>Address:</strong> {{ $user->address }}</p>

</div>
@foreach ($user->pets as $pet)
<div style="color: white; padding: 20px;">
    <div style="flex-shrink: 0; margin-right: 20px;">
        <img src="{{asset('storage/app/public/' . $pet->picture)}}" 
             alt="Profile Picture" 
             style="border-radius: 50%; width: 150px; height: 150px; object-fit: cover;">
    </div>

    <h2 style="border-bottom: 2px solid #555; padding-bottom: 10px;">Pet Details</h2>
    <p><strong>Pet ID:</strong> {{ $pet->id }}</p>
    <p><strong>Pet Name:</strong> {{ $pet->name }}</p>
    <p><strong>Age:</strong> {{ $pet->age }}</p>
    <p><strong>Type:</strong> {{ $pet->type }}</p>
    <p><strong>Gender:</strong> {{ $pet->gender }}</p>
    <p><strong>Breed:</strong> {{ $pet->breed }}</p>

</div>
@endforeach




@endsection