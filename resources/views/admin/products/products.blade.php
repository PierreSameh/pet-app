@extends('layouts.app')

@section('content')
{{-- @php
    dd($products);
@endphp --}}
<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.get.store', $storeId) }}" class="btn btn-secondary">
        ← Back to Store
    </a>
    <a href="{{route('admin.add.product', $storeId)}}" class="btn btn-success" style="float: right;">
        + Add Product
    </a>
</div>
@if (count($products) > 0)
    <!-- ShowStores.blade.php -->
<table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
    <thead>
        <tr style="background-color: #333; color: #fff;">
            <th style="padding: 12px 15px; text-align: left;">Product ID</th>
            <th style="padding: 12px 15px; text-align: left;">Category</th>
            <th style="padding: 12px 15px; text-align: left;">Product Image</th>
            <th style="padding: 12px 15px; text-align: left;">Product Name</th>
            <th style="padding: 12px 15px; text-align: left;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr style="border-bottom: 1px solid #ddd;">
            <td style="padding: 12px 15px;">{{ $product->id }}</td>
            <td style="padding: 12px 15px;">{{ $product->category->name }}</td>
            <td style="padding: 12px 15px;"><img style="width:50px;height:50px;" src="{{ asset('storage/' . $product->productImages->first()->image)}}" alt=""></td>
            <td style="padding: 12px 15px;"><a href="#">{{ $product->name }}</a></td>
            <td style="padding: 12px 15px;">
                <a href="{{route('admin.edit.product', $product->id)}}" style="text-decoration: none; color: #fff; background-color: #007bff; padding: 5px 10px; border-radius: 5px;">Edit</a>
                <form action="" method="POST" style="display: inline;">
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
<h1>There is no Products Yet</h1>
@endif
    
@endsection