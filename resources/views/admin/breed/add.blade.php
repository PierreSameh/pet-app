@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-body">
      <h4 class="card-title">Add New Breed</h4>
      <form class="forms-sample" method="POST" action="{{route('admin.breed.store')}}" enctype="multipart/form-data">
        @csrf
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="payment_type" style="color: white;">Payment Type</label>
            <select id="payment_type" name="type" class="form-control" required>
                <option value="" disabled selected>Select Type</option>
                <option value="dog">Dog</option>
                <option value="cat">Cat</option>
                <option value="bird">Bird</option>
                <option value="turtle">Turtle</option>
                <option value="fish">Fish</option>
                <option value="monkey">Monkey</option>
            </select>
        </div>
        <div class="form-group">
          <label for="exampleInputName1">Breed Name</label>
          <input type="text" class="form-control" id="exampleInputName1" placeholder="Breed Name" name="name">
        </div>
        <div class="form-group">
          <label for="exampleInputName1">Life Expectancy</label>
          <input type="text" class="form-control" id="exampleInputName1" placeholder="Life Expectancy" name="life_expectancy">
        </div>
        <div class="form-group">
          <label for="exampleInputName1">Weight</label>
          <input type="text" class="form-control" id="exampleInputName1" placeholder="Weight" name="weight">
        </div>
        <div class="form-group">
          <label for="exampleInputName1">Height</label>
          <input type="text" class="form-control" id="exampleInputName1" placeholder="Height" name="height">
        </div>
        <div class="form-group">
          <label for="exampleInputName1">Physical Charactaristcs</label>
          <input type="text" class="form-control" id="exampleInputName1" placeholder="Physical Charactaristcs" name="physical_charactaristcs">
        </div>
        <button type="submit" class="btn btn-primary mr-2">Submit</button>
      </form>
    </div>
  </div>

@endsection