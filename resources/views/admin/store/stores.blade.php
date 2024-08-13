@extends('layouts.app')

@section('content')
@if (count($stores) > 0)
    <!-- ShowStores.blade.php -->
<table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
    <thead>
        <tr style="background-color: #333; color: #fff;">
            <th style="padding: 12px 15px; text-align: left;">Store ID</th>
            <th style="padding: 12px 15px; text-align: left;">Store Name</th>
            <th style="padding: 12px 15px; text-align: left;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($stores as $store)
        <tr style="border-bottom: 1px solid #ddd;">
            <td style="padding: 12px 15px;">{{ $store->id }}</td>
            <td style="padding: 12px 15px;">{{ $store->name }}</td>
            <td style="padding: 12px 15px;">
                <a href="{{route('admin.edit.store', $store->id)}}" style="text-decoration: none; color: #fff; background-color: #007bff; padding: 5px 10px; border-radius: 5px;">Edit</a>
                <form action="{{route('admin.delete.store', $store->id)}}" method="POST" style="display: inline;">
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
<h1>There is no Stores</h1>
@endif

@endsection