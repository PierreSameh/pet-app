@extends('layouts.app')

@section('content')
<a href="{{ route('admin.breed.all') }}" class="btn btn-secondary">
    ← Back to Breeds
</a>

<h1 style="margin: 20px">Breed Details</h1>

<div style="padding: 20px; color: white;">
    <h2>Breed Name: {{ $breed->name }}</h2>
    <ul style="list-style: none; padding: 20px;">
        <li style="margin: 10px;"><strong>Type:</strong> {{ ucfirst($breed->type) }}</li>
        <li style="margin: 10px;"><strong>Life Expectancy:</strong> {{ $breed->life_expectancy }} years</li>
        <li style="margin: 10px;"><strong>Weight:</strong> {{ $breed->weight }} kg</li>
        <li style="margin: 10px;"><strong>Height:</strong> {{ $breed->height }} cm</li>
        <li style="margin: 10px;"><strong>Physical Characteristics:</strong> {{ $breed->physical_characteristics }}</li>
    </ul>
    <div style="margin-top: 20px;">
        <a href="{{route('admin.breed.edit', $breed->id)}}" class="btn btn-secondary">Edit</a>
        <form action="{{route('admin.breed.delete', $breed->id)}}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this breed?')">Delete</button>
        </form>
    </div>
</div>

@endsection
