@extends('layouts.app')

@section('content')
<a href="{{ route('admin.get.stores') }}" class="btn btn-secondary">
    ← Back to Stores
</a>
<div style="margin: 0; margin-top:20px; padding: 0; background-color: #121212; color: #ffffff; font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100%; box-sizing: border-box;">

    <div style="width: 80%; max-width: 1200px; display: flex; flex-direction: column; gap: 30px;">
        <!-- Store Image -->
        <div style="width: 100%; height: 400px; background-color: #2c2c2c; border-radius: 10px; overflow: hidden; display: flex; justify-content: center; align-items: center;">
            <img src="{{asset('storage/'. $store->picture)}}" alt="Store Image" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        
        <!-- Store Details -->
        <div style="display: flex; flex-direction: column; gap: 20px; text-align: center;">
            <h2 style="font-size: 2.5em; color: #ffffff;">{{$store->name}}</h2>
            <p style="font-size: 1.2em; color: #cccccc; line-height: 1.8;">{{$store->description}}</p>
        </div>
        
        <!-- Action Buttons -->
        <div style="display: flex; justify-content: center; gap: 20px;">
            <a href="{{route('admin.edit.store', $store->id)}}" style="padding: 15px 30px; font-size: 1.2em; border-radius: 5px; border: none; cursor: pointer; background-color: #6c757d; color: #ffffff;">Edit</a>
            <form action="{{route('admin.delete.store', $store->id)}}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" onclick="return confirm('are you sure?')"
                style="text-decoration: none; color: #fff; background-color: #dc3545; padding: 15px 30px; border-radius: 5px; border: none; cursor: pointer;">Delete</button>
            </form>
        </div>
    </div>
      <!-- Right Side Navigation -->
      <div style="width: 200px; background-color: #1f1f1f; padding: 20px; display: flex; flex-direction: column; gap: 15px;position: absolute;right: 0px;top: 150px;">
        <h3 style="color: #ffffff; font-size: 1.5em; text-align: center; margin-bottom: 20px;">Navigate</h3>
        <a href="{{route('admin.get.categories', $store->id)}}" style="color: #ffffff; text-decoration: none; font-size: 1.2em; padding: 10px; background-color: #333333; border-radius: 5px; text-align: center;">Categories</a>
        <a href="{{route('admin.get.products', $store->id)}}" style="color: #ffffff; text-decoration: none; font-size: 1.2em; padding: 10px; background-color: #333333; border-radius: 5px; text-align: center;">Products</a>
    </div>

</div>


@endsection