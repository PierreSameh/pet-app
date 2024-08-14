@extends('layouts.app')

@section('content')
<div class="card" style="margin-bottom: 20px;">
    <div class="card-body">
      <h4 class="card-title">Add New Category</h4>
      <form class="forms-sample" method="POST" action="{{route('admin.save.category', $storeId)}}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="store_id" value="{{$storeId}}">
        <div class="form-group">
          <label for="exampleInputName1">Name</label>
          <input type="text" class="form-control" id="exampleInputName1" placeholder="Name" name="name">
        </div>
        <div class="form-group">
          <label for="exampleTextarea1">Description</label>
          <textarea class="form-control" id="exampleTextarea1" rows="4" name="notes" placeholder="Description"></textarea>
        </div>
        <div class="form-group">
          <label>Image upload</label>
          <input type="file" name="img[]" class="file-upload-default">
          <div class="input-group col-xs-12">
            <input type="file" name="image" class="form-control file-upload-info" id="file" placeholder="Upload Image">
          </div>
        </div>
        <button type="submit" class="btn btn-primary mr-2">Submit</button>
      </form>
    </div>
  </div>


@if (count($categories) > 0)
    <!-- ShowStores.blade.php -->
<table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
    <thead>
        <tr style="background-color: #333; color: #fff;">
            <th style="padding: 12px 15px; text-align: left;">Store ID</th>
            <th style="padding: 12px 15px; text-align: left;">Store Name</th>
            <th style="padding: 12px 15px; text-align: left;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $category)
        <tr style="border-bottom: 1px solid #ddd;">
            <td style="padding: 12px 15px;">{{ $category->id }}</td>
            <td style="padding: 12px 15px;"><a href="#">{{ $category->name }}</a></td>
            <td style="padding: 12px 15px;">
                <a href="" style="text-decoration: none; color: #fff; background-color: #007bff; padding: 5px 10px; border-radius: 5px;">Edit</a>
                <form action="" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" onclick="return confirm('are you sure?')"
                    style="text-decoration: none; color: #fff; background-color: #dc3545; padding: 5px 10px; border-radius: 5px; border: none; cursor: pointer;">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<h1>There is no Categories</h1>
@endif

@endsection