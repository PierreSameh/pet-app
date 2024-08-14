@extends('layouts.app')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.get.products', $storeId) }}" class="btn btn-secondary">
        ← Back to Products
    </a>
</div>
<div class="card" style="margin-bottom: 20px;">
    <div class="card-body">
        <h4 class="card-title">Add New Product</h4>
        <form method="POST" action="{{route('admin.save.product')}}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="store_id" value="{{$storeId}}">
            <div class="form-group">
                <label for="category">Category</label>
                <select class="form-control" id="category" name="category_id" required>
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Product Name" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Product Description"></textarea>
            </div>
            <div class="form-group">
            <label for="type">Type</label>
            <select class="form-control" id="type" name="type" required>
                <option value="">Select a Type</option>
                    <option value="dog">Dog</option>
                    <option value="cat">Cat</option>
                    <option value="bird">Bird</option>
                    <option value="fish">Fish</option>
                    <option value="turtle">Turtle</option>
                    <option value="monkey">Monkey</option>
            </select>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="Price" required>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Quantity" required>
            </div>
            <div class="form-group">
                <label for="photos">Product Photos</label>
                <input type="file" class="form-control" id="photos" name="images[]" multiple>
            </div>
            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>
    </div>
</div>

{{-- <div class="card" style="margin-bottom: 20px;">
    <div class="card-body">
        <h4 class="card-title">Add New Product</h4>
        <!-- Main Product Form -->
        <form id="mainForm" method="POST" action="{{ route('admin.save.product') }}">
            @csrf
            <div class="form-group">
                <label for="category">Category</label>
                <select class="form-control" id="category" name="category_id" required>
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Product Name" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Product Description"></textarea>
            </div>
            <div class="form-group">
                <label for="type">Type</label>
                <select class="form-control" id="type" name="type" required>
                    <option value="">Select a Type</option>
                        <option value="dog">Dog</option>
                        <option value="cat">Cat</option>
                        <option value="bird">Bird</option>
                        <option value="fish">Fish</option>
                        <option value="turtle">Turtle</option>
                        <option value="monkey">Monkey</option>
                </select>
                </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="Price" required>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Quantity" required>
            </div>

            <!-- Hidden Submit Button for Main Form -->
            <button type="submit" id="mainSubmit" style="display: none;"></button>
        </form>

        <!-- Multiple Photos Form -->
        <form id="photosForm" method="POST" action="#" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="photos">Product Photos</label>
                <input type="file" class="form-control" id="photos" name="images[]" multiple>
            </div>

            <!-- Single Submit Button for Both Forms -->
            <button type="button" class="btn btn-primary" onclick="submitBothForms()">Add Product</button>
        </form>
    </div>
</div>

<script>
    function submitBothForms() {
        document.getElementById('mainSubmit').click();  // Submit the main form
        document.getElementById('photosForm').submit(); // Submit the photos form
    }
</script> --}}
@endsection
