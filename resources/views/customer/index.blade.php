@extends('layouts.customer')

@section('content')
<style>
    .product-card {
        height: 520px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .product-img {
        height: 280px;
        width: 100%;
        object-fit: cover;
        border-bottom: 1px solid #eee;
    }
    .product-details {
        height: 200px;
        overflow: hidden;
    }
    .filter-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
</style>

{{-- SEARCH + FILTER BAR --}}
<div class="filter-card p-3 mb-4">
    <div class="row g-2 align-items-end">
        <div class="col-md-4 position-relative">
            <input type="text" id="customer-search" class="form-control" placeholder="🔍 Search products..." autocomplete="off">
            <div id="customer-search-suggestions" class="position-absolute w-100 bg-white border rounded shadow-sm mt-1 d-none" style="z-index: 1000; max-height: 200px; overflow-y: auto;"></div>
        </div>
        <div class="col-md-3">
            <select id="customer-category" class="form-select">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select id="customer-sort" class="form-select">
                <option value="">Sort: Default</option>
                <option value="price-asc">Price: Low → High</option>
                <option value="price-desc">Price: High → Low</option>
            </select>
        </div>
        <div class="col-md-2">
            <button id="customer-filter-btn" class="btn btn-primary w-100">Filter</button>
        </div>
    </div>
</div>

{{-- PRODUCTS GRID --}}
<div class="row g-3" id="customer-products-wrapper">
    @forelse($products as $product)
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-0 product-card">
                @if($product->image)
                    <img src="{{ asset($product->image) }}" class="product-img">
                @else
                    <img src="https://via.placeholder.com/300x220?text=No+Image" class="product-img">
                @endif

                <div class="card-body product-details d-flex flex-column">
                    <h5 class="card-title fw-bold text-truncate">{{ $product->name }}</h5>
                    <p class="text-muted small">{{ Str::limit($product->details, 70) }}</p>
                    <ul class="list-unstyled mb-2 small text-muted flex-grow-1">
                        <li><strong>Category:</strong> {{ $product->category }}</li>
                        <li><strong>Size:</strong> {{ $product->size }}</li>
                        <li><strong>Color:</strong> {{ $product->color }}</li>
                    </ul>
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <span class="fw-bold text-success fs-5">₹{{ number_format($product->price) }}</span>
                        @if($product->stock > 0)
                            <a href="{{ route('customer.products.show', $product) }}" class="btn btn-sm btn-primary">View Details</a>
                        @else
                            <span class="badge bg-danger">Out of Stock</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5 text-muted">
            <h4>No products found</h4>
            <p>Try adjusting your search or filters</p>
        </div>
    @endforelse
</div>

{{-- PAGINATION --}}
<div class="mt-4 d-flex justify-content-center">
    {{ $products->links() }}
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    function fetchCustomerProducts(page = 1) {
        const keyword = $('#customer-search').val();
        const category = $('#customer-category').val();
        const sort = $('#customer-sort').val();

        $.ajax({
            url: "{{ route('customer.products') }}",
            type: "GET",
            data: { page, keyword, category, sort },
            success: function(data) {
                $('#customer-products-wrapper').html($(data).find('#customer-products-wrapper').html());
                $('.pagination a').off('click').on('click', function(e){
                    e.preventDefault();
                    let page = $(this).attr('href').split('page=')[1];
                    fetchCustomerProducts(page);
                });
            }
        });
    }

    $('#customer-search').on('keyup', function(){
        const keyword = $(this).val();
        const suggestionsContainer = $('#customer-search-suggestions');

        if (keyword.length < 2) {
            suggestionsContainer.addClass('d-none').empty();
            return;
        }

        $.ajax({
            url: "{{ route('customer.products.suggestions') }}",
            type: "GET",
            data: { keyword: keyword },
            success: function(data) {
                if (data.length > 0) {
                    let html = '';
                    data.forEach(function(item) {
                        html += '<div class="customer-suggestion-item p-2 border-bottom" data-keyword="' + item.label + '" style="cursor: pointer;">' +
                                '<div class="d-flex justify-content-between">' +
                                '<span>' + item.label + '</span>' +
                                '<span class="badge bg-secondary small">' + item.type + '</span>' +
                                '</div></div>';
                    });
                    suggestionsContainer.html(html).removeClass('d-none');
                } else {
                    suggestionsContainer.addClass('d-none').empty();
                }
            }
        });
    });

    $(document).on('click', '.customer-suggestion-item', function(){
        $('#customer-search').val($(this).data('keyword'));
        $('#customer-search-suggestions').addClass('d-none').empty();
        fetchCustomerProducts(1);
    });

    $(document).on('click', function(e){
        if (!$(e.target).closest('#customer-search-suggestions, #customer-search').length) {
            $('#customer-search-suggestions').addClass('d-none').empty();
        }
    });

    $('#customer-category').on('change', function(){
        fetchCustomerProducts(1);
    });

    $('#customer-sort').on('change', function(){
        fetchCustomerProducts(1);
    });

    $('#customer-filter-btn').on('click', function(){
        fetchCustomerProducts(1);
    });
});
</script>
@endpush
