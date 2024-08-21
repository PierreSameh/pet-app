@extends('layouts.app')

@section('content')
@if (count($messages) > 0)
    <!-- ShowStores.blade.php -->
<table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
    <thead>
        <tr style="background-color: #333; color: #fff;">
            <th style="padding: 12px 15px; text-align: left;">Message ID</th>
            <th style="padding: 12px 15px; text-align: left;">User ID</th>
            <th style="padding: 12px 15px; text-align: left;">Name</th>
            <th style="padding: 12px 15px; text-align: left;">Sent Date</th>
            <th style="padding: 12px 15px; text-align: left;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($messages as $message)
        <tr style="border-bottom: 1px solid #ddd;">
            <td style="padding: 12px 15px;">{{ $message->id }}</td>
            <td style="padding: 12px 15px;">{{ $message->user_id }}</td>
            <td style="padding: 12px 15px;">{{ $message->user->first_name . " " . $message->user->last_name }}</td>
            <td style="padding: 12px 15px;">{{ $message->created_at }}</td>
            <td style="padding: 12px 15px;">
                <a href="{{ route('admin.support.details', $message->id) }}" style="text-decoration: none; color: #fff; background-color: #007bff; padding: 5px 10px; border-radius: 5px;">Message Details</a>
                <form action="{{ route('admin.support.delete', $message->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" onclick="return confirm('are you sure?')"
                    style="text-decoration: none; color: #fff; background-color: #dc3545; padding: 5px 10px; border-radius: 5px; border: none; cursor: pointer;">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<h1>There is no Messages Yet</h1>
@endif
    
@endsection