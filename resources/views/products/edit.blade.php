@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h1 class="fw-bold mb-4">✏ Edit Product</h1>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                        @error('name')<span class="text-danger small">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Category</label>
                        <input type="text" name="category" class="form-control" value="{{ old('category', $product->category) }}" list="category-list" required>
                        <datalist id="category-list">
                            @foreach($categories as $category)
                                <option value="{{ $category }}">
                            @endforeach
                        </datalist>
                        @error('category')<span class="text-danger small">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Details</label>
                        <textarea name="details" class="form-control" rows="2" required>{{ old('details', $product->details) }}</textarea>
                        @error('details')<span class="text-danger small">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Size</label>
                        <input type="text" name="size" class="form-control" value="{{ old('size', $product->size) }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Color</label>
                        <input type="text" name="color" class="form-control" value="{{ old('color', $product->color) }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Price (₹)</label>
                        <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
                        @error('price')<span class="text-danger small">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Stock Quantity</label>
                        <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" min="0" required>
                        @error('stock')<span class="text-danger small">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Low Stock Alert At</label>
                        <input type="number" name="low_stock_threshold" class="form-control" value="{{ old('low_stock_threshold', $product->low_stock_threshold ?? 5) }}" min="1" required>
                        @error('low_stock_threshold')<span class="text-danger small">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Current Image</label><br>
                        @if($product->image)
                            <img src="{{ asset($product->image) }}" width="100" class="border rounded mb-2">
                        @else
                            <p class="text-muted">No Image</p>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Replace Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">Leave blank to keep current image</small>
                        @error('image')<span class="text-danger small">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Product</button>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Back to List</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
