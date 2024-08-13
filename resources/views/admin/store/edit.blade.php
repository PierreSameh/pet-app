@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-body">
      <h4 class="card-title">Edit Store</h4>
      <form class="forms-sample" method="POST" action="{{route('admin.excuteedit.store', $store->id)}}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
          <label for="exampleInputName1">Name</label>
          <input type="text" class="form-control" id="exampleInputName1" placeholder="Name" value="{{$store->name}}" name="name">
        </div>
        <div class="form-group">
          <label>Image upload</label>
          <input type="file" name="img[]" class="file-upload-default">
          <div class="input-group col-xs-12">
            <input type="file" name="picture" class="form-control file-upload-info" id="file" placeholder="Upload Image">
            {{-- <span class="input-group-append" style="position: absolute;height:37px;right:267px;">
              <input class="file-upload-browse btn btn-primary" type="button"
              onclick="document.getElementById('file').click()" 
              value="Select Image"></input>
            </span> --}}
          </div>
        </div>
        <button type="submit" class="btn btn-primary mr-2">Submit</button>
      </form>
    </div>
  </div>

@endsection