@extends('layouts.app')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.get.categories', $store) }}" class="btn btn-secondary">
        ← Back to Categories
    </a>
</div>
<div class="card" style="margin-bottom: 20px;">
    <div class="card-body">
      <h4 class="card-title">Edit Category</h4>
      <form class="forms-sample" method="POST" action="{{route('admin.update.category', $category->id)}}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
          <label for="exampleInputName1">Name</label>
          <input type="text" class="form-control" id="exampleInputName1" value="{{$category->name}}" placeholder="Name" name="name">
        </div>
        <div class="form-group">
          <label for="exampleTextarea1">Description</label>
          <textarea class="form-control" id="exampleTextarea1" rows="4" name="notes" placeholder="Description">{{$category->notes}}</textarea>
        </div>
        <div class="form-group">
          <label>Image upload</label>
          <input type="file" name="img[]" class="file-upload-default">
          <div class="input-group col-xs-12">
            <input type="file" name="image" class="form-control file-upload-info" id="file" placeholder="Upload Image">
          </div>
        </div>
        <button type="submit" class="btn btn-primary mr-2">Update</button>
      </form>
    </div>
  </div>
@endsection