@extends('layouts.app')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.get.products', $store) }}" class="btn btn-secondary">
        ← Back to Products
    </a>
</div>

@if(count($images) > 0)
<div class="row" style="margin-bottom: 50px;">
    @foreach($images as $image)
    <div class="col-md-3" style="margin-bottom: 20px;">
        <div class="card">
            <img src="{{ asset('storage/app/public/' . $image->image) }}" class="card-img-top" alt="Product Image">
            <div class="card-body text-center">
                <form action="{{route('admin.delete.productimage', $image->id)}}" method="POST" onsubmit="return confirm('Are you sure you want to delete this image?');">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Delete Image</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
<div class="card" style="margin-bottom: 20px;">
    <div class="card-body">
        <h4 class="card-title">Edit Product</h4>
        <form method="POST" action="{{route('admin.update.product', $product->id)}}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="category">Category</label>
                <select class="form-control" id="category" name="category_id" required>
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? "selected" : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{$product->name}}" placeholder="Product Name" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Product Description">{{$product->description}}</textarea>
            </div>
            <div class="form-group">
                <label for="type">Type</label>
                <select class="form-control" id="type" name="type" required>
                    <option value="">Select a Type</option>
                    <option value="dog" {{ $product->type == "dog" ? 'selected' : '' }}>Dog</option>
                    <option value="cat" {{ $product->type == "cat" ? 'selected' : '' }}>Cat</option>
                    <option value="bird" {{ $product->type == "bird" ? 'selected' : '' }}>Bird</option>
                    <option value="fish" {{ $product->type == "fish" ? 'selected' : '' }}>Fish</option>
                    <option value="turtle" {{ $product->type == "turtle" ? 'selected' : '' }}>Turtle</option>
                    <option value="monkey" {{ $product->type == "monkey" ? 'selected' : '' }}>Monkey</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" step="0.01" class="form-control" id="price" value="{{$product->price}}" name="price" placeholder="Price" required>
            </div>
            <div class="form-group">
                <label for="price">Sale Price</label>
                <input type="number" step="0.01" class="form-control" id="price" value="{{$product->sale_amount}}" name="sale_amount" placeholder="Put The Sale Price">
            </div>
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" class="form-control" id="quantity" value="{{$product->quantity}}" name="quantity" placeholder="Quantity" required>
            </div>
            <div class="form-group">
                <label for="photos">Product Photos</label>
                <input type="file" class="form-control" id="photos" name="images[]" multiple>
            </div>
            <button type="submit" class="btn btn-primary">Edit</button>
        </form>
    </div>
</div>


@endsection
