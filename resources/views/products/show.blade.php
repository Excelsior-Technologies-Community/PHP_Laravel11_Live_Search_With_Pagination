@extends('layouts.admin')

@section('content')

<div class="container-fluid py-4">

    {{-- ========================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-1">

                <span style="font-size: 30px;">
                    📦
                </span>

                <h2 class="fw-bold mb-0">
                    Product Details
                </h2>

            </div>

            <p class="text-muted mb-0">
                View complete product information
            </p>

        </div>


        <div class="d-flex gap-2 flex-wrap">

            <a
                href="{{ route('products.index') }}"
                class="btn btn-secondary">
                ← Back
            </a>


            <a
                href="{{ route('products.edit', $product) }}"
                class="btn btn-warning">
                ✏️ Edit Product
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- PRODUCT CARD --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm overflow-hidden">

        <div class="card-body p-4">

            <div class="row g-4">

                {{-- ================================================= --}}
                {{-- IMAGE --}}
                {{-- ================================================= --}}

                <div class="col-lg-4">

                    <div
                        class="border rounded-4 p-3 text-center bg-light h-100">

                        @if(
                        $product->image &&
                        file_exists(public_path($product->image))
                        )

                        <div
                            class="d-flex align-items-center justify-content-center"
                            style="min-height: 350px;">

                            <img
                                src="{{ asset($product->image) }}"
                                alt="{{ $product->name }}"
                                class="img-fluid rounded-4"
                                style="
                                        max-height: 350px;
                                        max-width: 100%;
                                        object-fit: contain;
                                    ">

                        </div>

                        @else

                        <div
                            class="d-flex flex-column align-items-center justify-content-center"
                            style="height: 350px;">

                            <div
                                style="font-size: 80px;">
                                📦
                            </div>

                            <div class="text-muted mt-2">
                                No Image Available
                            </div>

                        </div>

                        @endif

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- DETAILS --}}
                {{-- ================================================= --}}

                <div class="col-lg-8">

                    <div
                        class="d-flex justify-content-between align-items-start gap-3 mb-4">

                        <div>

                            <h3 class="fw-bold mb-2">
                                {{ $product->name }}
                            </h3>


                            @if($product->status === 'active')

                            <span class="badge bg-success px-3 py-2">
                                🟢 Active
                            </span>

                            @else

                            <span class="badge bg-secondary px-3 py-2">
                                ⚪ Inactive
                            </span>

                            @endif

                        </div>


                        <div class="text-end">

                            <div class="text-muted small">
                                Product ID
                            </div>

                            <div class="fw-bold fs-5">
                                #{{ $product->id }}
                            </div>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- PRICE --}}
                    {{-- ================================================= --}}

                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            Price
                        </div>

                        <div class="fs-1 fw-bold text-primary">
                            ₹{{ number_format($product->price, 2) }}
                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- INFORMATION --}}
                    {{-- ================================================= --}}

                    <div class="row g-3">

                        {{-- CATEGORY --}}

                        <div class="col-md-6">

                            <div class="border rounded-3 p-3 h-100">

                                <div class="text-muted small mb-1">
                                    Category
                                </div>

                                <div class="fw-semibold">
                                    {{ $product->category ?: 'N/A' }}
                                </div>

                            </div>

                        </div>


                        {{-- SIZE --}}

                        <div class="col-md-6">

                            <div class="border rounded-3 p-3 h-100">

                                <div class="text-muted small mb-1">
                                    Size
                                </div>

                                <div class="fw-semibold">
                                    {{ $product->size ?: 'N/A' }}
                                </div>

                            </div>

                        </div>


                        {{-- COLOR --}}

                        <div class="col-md-6">

                            <div class="border rounded-3 p-3 h-100">

                                <div class="text-muted small mb-1">
                                    Color
                                </div>

                                <div class="fw-semibold">
                                    {{ $product->color ?: 'N/A' }}
                                </div>

                            </div>

                        </div>


                        {{-- STOCK --}}

                        <div class="col-md-6">

                            <div class="border rounded-3 p-3 h-100">

                                <div class="text-muted small mb-1">
                                    Stock
                                </div>

                                <div class="fw-semibold">

                                    @if($product->stock <= 0)

                                        <span class="text-danger">
                                        ❌ Out of Stock
                                        </span>

                                        @elseif(
                                        $product->stock <=
                                            ($product->low_stock_threshold ?? 5)
                                            )

                                            <span class="text-warning">
                                                ⚠️
                                                {{ $product->stock }}
                                                — Low Stock
                                            </span>

                                            @else

                                            <span class="text-success">
                                                ✅
                                                {{ $product->stock }}
                                                — In Stock
                                            </span>

                                            @endif

                                </div>

                            </div>

                        </div>


                        {{-- LOW STOCK THRESHOLD --}}

                        <div class="col-md-6">

                            <div class="border rounded-3 p-3 h-100">

                                <div class="text-muted small mb-1">
                                    Low Stock Threshold
                                </div>

                                <div class="fw-semibold">
                                    {{ $product->low_stock_threshold }}
                                </div>

                            </div>

                        </div>


                        {{-- ADDED ON --}}

                        <div class="col-md-6">

                            <div class="border rounded-3 p-3 h-100">

                                <div class="text-muted small mb-1">
                                    Added On
                                </div>

                                <div class="fw-semibold">

                                    {{ optional($product->created_at)->format('d M Y, h:i A') }}

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- DESCRIPTION --}}
                    {{-- ================================================= --}}

                    <div class="mt-4">

                        <h5 class="fw-bold mb-2">
                            Product Details
                        </h5>

                        <div
                            class="border rounded-3 p-3 bg-light"
                            style="white-space: normal; line-height: 1.7;">

                            {!! nl2br(e($product->details)) !!}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection