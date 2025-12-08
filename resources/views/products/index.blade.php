@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <!-- Header section with title and Add New Product button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">📦 Products List</h2>
        <a href="{{ route('products.create') }}" class="btn btn-primary">➕ Add New Product</a>
    </div>

    <!-- Success message display from session flash data -->
    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif

    <!-- AJAX SEARCH + SORTING CONTROLS -->
    <div class="mb-3 row g-3 align-items-end">
        <div class="col-md-5">
            <!-- Live search input (triggers on keyup) -->
            <input type="text" id="search" class="form-control" placeholder="Search products...">
        </div>

        <div class="col-md-3">
            <!-- Price sorting dropdown -->
            <select id="sort" class="form-select">
                <option value="">Default Sorting</option>
                <option value="price-asc">Price: Low to High</option>
                <option value="price-desc">Price: High to Low</option>
            </select>
        </div>

        <div class="col-md-4">
            <!-- Manual filter button -->
            <button id="filter-btn" class="btn btn-outline-primary w-100">🔍 Filter</button>
        </div>
    </div>

    <!-- MAIN PRODUCT TABLE CONTAINER -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <!-- Table wrapper for AJAX content replacement -->
            <div class="table-responsive" id="product-table-wrapper">
                <!-- Responsive hover table -->
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th width="20%">Details</th>
                            <th>Image</th>
                            <th>Size</th>
                            <th>Color</th>
                            <th>Category</th>
                            <th>Price (₹)</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <!-- Loop through paginated products from controller -->
                        @forelse($products as $product)
                            <tr>
                                <!-- Product name (bold) -->
                                <td class="fw-semibold">{{ $product->name }}</td>

                                <!-- Truncated details with line wrapping -->
                                <td style="white-space: normal;">
                                    {{ Str::limit($product->details, 60) }}
                                </td>

                                <!-- SINGLE IMAGE PREVIEW -->
                                <td>
                                    @if($product->image)
                                        <!-- Show product image if exists -->
                                        <img src="{{ asset($product->image) }}" width="70"
                                             class="rounded shadow-sm border">
                                    @else
                                        <!-- No image placeholder -->
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>

                                <!-- Product attributes -->
                                <td>{{ $product->size }}</td>
                                <td>{{ $product->color }}</td>
                                <td>{{ $product->category }}</td>

                                <!-- Formatted Indian Rupee price -->
                                <td class="fw-bold text-success">
                                    ₹{{ number_format($product->price) }}
                                </td>

                                <!-- Action buttons column -->
                                <td class="text-center">
                                    <!-- Edit button with route model binding -->
                                    <a href="{{ route('products.edit', $product) }}"
                                       class="btn btn-warning btn-sm me-1">✏ Edit</a>

                                    <!-- Delete form (method spoofing) -->
                                    <form action="{{ route('products.destroy', $product) }}"
                                          method="POST" class="d-inline">
                                        @csrf              <!-- CSRF protection -->
                                        @method('DELETE')  <!-- HTTP method spoofing -->
                                        <button class="btn btn-danger btn-sm"
                                                onclick="return confirm('Delete this product?')">
                                            🗑 Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>

                        @empty
                            <!-- Empty state row spanning all columns -->
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    No products found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Laravel Pagination Links (AJAX enabled) -->
                <div class="mt-3 px-3">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- jQuery CDN for AJAX functionality -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
$(document).ready(function(){
    // CORE AJAX FUNCTION - fetches filtered/paginated data
    function fetch_data(page = 1, keyword = '', sort = '') {
        $.ajax({
            url: "{{ route('products.index') }}",     // Same controller method
            type: "GET",
            data: { page, keyword, sort },           // Pass current filters
            success: function(data) {
                // Replace ONLY table content (preserves search controls)
                $('#product-table-wrapper').html($(data).find('#product-table-wrapper').html());
            }
        });
    }

    // LIVE SEARCH - triggers on every keystroke
    $('#search').on('keyup', function(){
        fetch_data(1, $('#search').val(), $('#sort').val());
    });

    // SORTING - triggers on dropdown change
    $('#sort').on('change', function(){
        fetch_data(1, $('#search').val(), $('#sort').val());
    });

    // MANUAL FILTER BUTTON
    $('#filter-btn').on('click', function(){
        fetch_data(1, $('#search').val(), $('#sort').val());
    });

    // PAGINATION - preserves search/sort state
    $(document).on('click', '.pagination a', function(e){
        e.preventDefault();
        // Extract page number from pagination link
        let page = $(this).attr('href').split('page=')[1];
        fetch_data(page, $('#search').val(), $('#sort').val());
    });
});
</script>
@endpush
