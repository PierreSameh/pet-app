@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-body">
      <h4 class="card-title">Update Breed</h4>
      <form class="forms-sample" method="POST" action="{{route('admin.breed.update', $breed->id)}}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
          <label for="exampleInputName1">Breed Name</label>
          <input type="text" class="form-control" id="exampleInputName1" value="{{$breed->name}}" placeholder="Breed Name" name="name">
        </div>
        <div class="form-group">
          <label for="exampleInputName1">Life Expectancy</label>
          <input type="text" class="form-control" id="exampleInputName1" value="{{$breed->life_expectancy}}" placeholder="Life Expectancy" name="life_expectancy">
        </div>
        <div class="form-group">
          <label for="exampleInputName1">Weight</label>
          <input type="text" class="form-control" id="exampleInputName1" value="{{$breed->weight}}" placeholder="Weight" name="weight">
        </div>
        <div class="form-group">
          <label for="exampleInputName1">Height</label>
          <input type="text" class="form-control" id="exampleInputName1" value="{{$breed->height}}" placeholder="Height" name="height">
        </div>
        <div class="form-group">
          <label for="exampleInputName1">Physical Charactaristcs</label>
          <input type="text" class="form-control" id="exampleInputName1" value="{{$breed->physical_charactaristcs}}" placeholder="Physical Charactaristcs" name="physical_charactaristcs">
        </div>
        <button type="submit" class="btn btn-primary mr-2">Update</button>
      </form>
    </div>
  </div>

@endsection