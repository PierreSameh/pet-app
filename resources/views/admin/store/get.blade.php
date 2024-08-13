@extends('layouts.app')

@section('content')
<div style="margin: 0; padding: 0; background-color: #121212; color: #ffffff; font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100%; box-sizing: border-box;">

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
            <button style="padding: 15px 30px; font-size: 1.2em; border-radius: 5px; border: none; cursor: pointer; background-color: #6c757d; color: #ffffff;">Edit</button>
            <button style="padding: 15px 30px; font-size: 1.2em; border-radius: 5px; border: none; cursor: pointer; background-color: #dc3545; color: #ffffff;">Delete</button>
        </div>
    </div>

</div>

@endsection