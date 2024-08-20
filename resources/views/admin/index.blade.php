@extends('layouts.app')

@section('content')

<h1>Welcome Admin</h1>
<div style="padding: 20px;">
    <h2 style="color: white;">Add Payment Information</h2>
    <form action="{{ route('admin.add.payment') }}" method="POST">
        @csrf
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="payment_name" style="color: white;">Payment Name</label>
            <input type="text" id="payment_name" name="name" class="form-control" value="{{$payment->name}}" required>
        </div>
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="payment_number" style="color: white;">Payment Number</label>
            <input type="text" id="payment_number" name="number" class="form-control" placeholder="{{$payment->number}}" required>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>


@endsection