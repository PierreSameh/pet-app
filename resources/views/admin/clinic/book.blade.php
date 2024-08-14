@extends('layouts.app')

@section('content')

@if (count($books) > 0)
    <!-- ShowStores.blade.php -->
<table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
    <thead>
        <tr style="background-color: #333; color: #fff;">
            <th style="padding: 12px 15px; text-align: left;">Clinic ID</th>
            <th style="padding: 12px 15px; text-align: left;">Clinic Name</th>
            <th style="padding: 12px 15px; text-align: left;">Client Name</th>
            <th style="padding: 12px 15px; text-align: left;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($books as $book)
        <tr style="border-bottom: 1px solid #ddd;">
            <td style="padding: 12px 15px;">{{ $book->id }}</td>
            <td style="padding: 12px 15px;">{{ $book->clinic->clinic_name }}</td>
            <td style="padding: 12px 15px;">{{ $book->user->first_name . ' ' . $book->user->last_name }}</td>
            <td style="padding: 12px 15px;">
                <a href="{{route('admin.books.details', $book->id)}}" style="text-decoration: none; color: #fff; background-color: #007bff; padding: 5px 10px; border-radius: 5px;">Book Details</a>
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
<h1>There is no Clinics</h1>
@endif

@endsection