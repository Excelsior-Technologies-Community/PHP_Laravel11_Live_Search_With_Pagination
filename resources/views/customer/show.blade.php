@extends('layouts.customer')

@section('content')
<style>
    .product-image-main {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .product-detail-card {
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 0;
    }
    .price-tag {
        font-size: 2rem;
        font-weight: 700;
        color: #10B981;
    }
    .stock-badge {
        font-size: 0.9rem;
        padding: 8px 16px;
        border-radius: 20px;
    }
    .specs-list dt {
        font-weight: 600;
        color: #6B7280;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .specs-list dd {
        font-weight: 500;
        color: #111827;
    }
</style>

<div class="py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('customer.products') }}">Products</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- PRODUCT IMAGE --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm product-detail-card">
                <div class="card-body p-3">
                    @if($product->image)
                        <img src="{{ asset($product->image) }}" class="product-image-main" alt="{{ $product->name }}">
                    @else
                        <img src="https://via.placeholder.com/600x400?text=No+Image" class="product-image-main" alt="No Image">
                    @endif
                </div>
            </div>
        </div>

        {{-- PRODUCT INFO --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm product-detail-card h-100">
                <div class="card-body p-4">
                    <h2 class="fw-bold mb-2">{{ $product->name }}</h2>
                    <p class="text-muted mb-3">{{ $product->details }}</p>

                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="price-tag">₹{{ number_format($product->price, 2) }}</span>
                        @if($product->stock > 0)
                            <span class="badge bg-success stock-badge">In Stock ({{ $product->stock }})</span>
                        @else
                            <span class="badge bg-danger stock-badge">Out of Stock</span>
                        @endif
                    </div>

                    <hr class="my-3">

                    <dl class="row specs-list mb-3">
                        <dt class="col-sm-3">Category</dt>
                        <dd class="col-sm-9">{{ $product->category }}</dd>

                        <dt class="col-sm-3">Size</dt>
                        <dd class="col-sm-9">{{ $product->size }}</dd>

                        <dt class="col-sm-3">Color</dt>
                        <dd class="col-sm-9">{{ $product->color }}</dd>
                    </dl>

                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ route('customer.products') }}" class="btn btn-outline-secondary btn-lg">← Back to Products</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RELATED PRODUCTS --}}
    @if($relatedProducts->count() > 0)
        <div class="mt-5">
            <h4 class="fw-bold mb-3">📦 Related Products</h4>
            <div class="row g-3">
                @foreach($relatedProducts as $related)
                    <div class="col-md-3 col-sm-6">
                        <div class="card shadow-sm border-0 product-card">
                            @if($related->image)
                                <img src="{{ asset($related->image) }}" class="product-img">
                            @else
                                <img src="https://via.placeholder.com/300x220?text=No+Image" class="product-img">
                            @endif
                            <div class="card-body product-details d-flex flex-column">
                                <h5 class="card-title fw-bold text-truncate">{{ $related->name }}</h5>
                                <p class="text-muted small">{{ Str::limit($related->details, 60) }}</p>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <span class="fw-bold text-success">₹{{ number_format($related->price) }}</span>
                                    <a href="{{ route('customer.products.show', $related) }}" class="btn btn-sm btn-outline-primary">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
