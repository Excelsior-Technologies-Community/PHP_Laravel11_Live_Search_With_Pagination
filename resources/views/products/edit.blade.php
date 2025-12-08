@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Edit Product</h1>

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- NAME -->
        <div class="mb-3">
            <label class="form-label fw-bold">Name</label>
            <input type="text" name="name" class="form-control"
                   value="{{ old('name', $product->name) }}" required>
        </div>

        <!-- DETAILS -->
        <div class="mb-3">
            <label class="form-label fw-bold">Details</label>
            <textarea name="details" class="form-control" required>{{ old('details', $product->details) }}</textarea>
        </div>

        <!-- SHOW OLD IMAGE -->
        <div class="mb-3">
            <label class="form-label fw-bold">Current Image</label><br>

            @if($product->image)
                <img src="{{ asset($product->image) }}" width="100" class="border rounded mb-2">
            @else
                <p class="text-muted">No Image Found</p>
            @endif
        </div>

        <!-- UPLOAD NEW IMAGE -->
        <div class="mb-3">
            <label class="form-label fw-bold">Upload New Image</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            <small class="text-muted">Leave blank if you don’t want to replace the image.</small>
        </div>

        <!-- SIZE -->
        <div class="mb-3">
            <label class="form-label fw-bold">Size</label>
            <input type="text" name="size" class="form-control"
                   value="{{ old('size', $product->size) }}" required>
        </div>

        <!-- COLOR -->
        <div class="mb-3">
            <label class="form-label fw-bold">Color</label>
            <input type="text" name="color" class="form-control"
                   value="{{ old('color', $product->color) }}" required>
        </div>

        <!-- CATEGORY -->
        <div class="mb-3">
            <label class="form-label fw-bold">Category</label>
            <input type="text" name="category" class="form-control"
                   value="{{ old('category', $product->category) }}" required>
        </div>

        <!-- PRICE -->
        <div class="mb-3">
            <label class="form-label fw-bold">Price</label>
            <input type="number" name="price" class="form-control"
                   value="{{ old('price', $product->price) }}" required>
        </div>

        <!-- SUBMIT -->
        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary mt-3">Back</a>

    </form>
</div>
@endsection
