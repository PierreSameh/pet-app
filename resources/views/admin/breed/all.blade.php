@extends('layouts.app')

@section('content')

<h1>Welcome Admin</h1>

<div style="padding: 20px;">
    <h2 style="color: white;">Breeds Management</h2>

    <div class="form-group" style="margin-bottom: 15px;">
        <label for="breed_type" style="color: white;">Select Animal Type</label>
        <select id="breed_type" class="form-control" onchange="filterBreeds()">
            <option value="all">All</option>
            <option value="dog">Dogs</option>
            <option value="cat">Cats</option>
            <option value="bird">Birds</option>
            <option value="turtle">Turtles</option>
            <option value="fish">Fishes</option>
            <option value="monkey">Monkeys</option>
        </select>
    </div>

    <table class="table table-striped" id="breeds_table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="breeds_tbody">
            @foreach($breeds as $breed)
            <tr data-type="{{ $breed->type }}">
                <td><a href="{{route('admin.breed.get', $breed->id)}}">{{ $breed->name }}</a></td>
                <td>{{$breed->type}}</td>
                <td>
                    <a href="{{route('admin.breed.edit', $breed->id)}}" class="btn btn-secondary">Edit</a>
                    <form action="{{route('admin.breed.delete', $breed->id)}}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this breed?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    function filterBreeds() {
        const type = document.getElementById('breed_type').value;
        const rows = document.querySelectorAll('#breeds_table tbody tr');

        rows.forEach(row => {
            if (type === 'all' || row.getAttribute('data-type') === type) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>

@endsection
