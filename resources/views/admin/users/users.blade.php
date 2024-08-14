@extends('layouts.app')

@section('content')
@if (count($users) > 0)
    <!-- ShowStores.blade.php -->
<table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
    <thead>
        <tr style="background-color: #333; color: #fff;">
            <th style="padding: 12px 15px; text-align: left;">User ID</th>
            <th style="padding: 12px 15px; text-align: left;">Name</th>
            <th style="padding: 12px 15px; text-align: left;">Email</th>
            <th style="padding: 12px 15px; text-align: left;">Joined</th>
            <th style="padding: 12px 15px; text-align: left;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr style="border-bottom: 1px solid #ddd;">
            <td style="padding: 12px 15px;">{{ $user->id }}</td>
            <td style="padding: 12px 15px;">{{ $user->first_name . " " . $user->last_name }}</td>
            <td style="padding: 12px 15px;">{{ $user->email }}</td>
            <td style="padding: 12px 15px;">{{ $user->created_at }}</td>
            <td style="padding: 12px 15px;">
                <a href="" style="text-decoration: none; color: #fff; background-color: #007bff; padding: 5px 10px; border-radius: 5px;">User Details</a>
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
<h1>There is no Users Yet</h1>
@endif
    
@endsection