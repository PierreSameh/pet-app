@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-body">
      <h4 class="card-title">Edit Clinic</h4>
      <form class="forms-sample" method="POST" action="{{route('admin.update.clinic', $clinic->id)}}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
          <label for="exampleInputName1">Clinic Name</label>
          <input type="text" class="form-control" id="exampleInputName1" value="{{$clinic->clinic_name}}" placeholder="Clinic Name" name="clinic_name">
        </div>
        <div class="form-group">
          <label for="exampleInputName1">Doctor</label>
          <input type="text" class="form-control" id="exampleInputName1" value="{{$clinic->doctor}}" placeholder="Doctor's Name" name="doctor">
        </div>
        <div class="form-group">
          <label for="exampleInputName1">Specialization</label>
          <input type="text" class="form-control" id="exampleInputName1" value="{{$clinic->specialization}}" placeholder="Specialization" name="specialization">
        </div>
        <div class="form-group">
          <label for="exampleInputName1">Address</label>
          <input type="text" class="form-control" id="exampleInputName1" value="{{$clinic->address}}" placeholder="Clinic's Address" name="address">
        </div>
        <div class="form-group">
          <label for="exampleInputName1">Medical Fees</label>
          <input type="text" class="form-control" id="exampleInputName1" value="{{$clinic->medical_fees}}" placeholder="price $" name="medical_fees">
        </div>
        <div class="form-group">
          <label for="exampleInputName1">Working Days</label>
          <input type="text" class="form-control" id="exampleInputName1" value="{{$clinic->working_days}}" placeholder="ex: Mon - Sat" name="working_days">
        </div>
        <div class="form-group">
          <label for="exampleInputName1">Working Times</label>
          <input type="text" class="form-control" id="exampleInputName1" value="{{$clinic->working_times}}" placeholder="ex: 10 AM : 9:30 PM" name="working_times">
        </div>
        <div class="form-group">
          <label>Image upload</label>
          <input type="file" name="img[]" class="file-upload-default">
          <div class="input-group col-xs-12">
            <input type="file" name="picture" class="form-control file-upload-info" id="file" placeholder="Upload Image">
          </div>
        </div>
        <button type="submit" class="btn btn-primary mr-2">Submit</button>
      </form>
    </div>
  </div>

@endsection