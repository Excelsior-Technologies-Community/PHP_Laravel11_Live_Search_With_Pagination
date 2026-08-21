@extends('layouts.admin')

@section('content')

<div class="container py-4">

    {{-- PAGE HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="fw-bold mb-0">
            📦 Products List
        </h2>

        <a
            href="{{ route('products.create') }}"
            class="btn btn-primary"
        >
            ➕ Add New Product
        </a>

    </div>


    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))

        <div class="alert alert-success shadow-sm">
            {{ session('success') }}
        </div>

    @endif


    {{-- SEARCH + FILTER --}}
    <div class="card shadow-sm border-0 mb-3">

        <div class="card-body">

            <div class="row g-3 align-items-end">

                {{-- SEARCH --}}
                <div class="col-md-4 position-relative">

                    <label class="form-label fw-bold small text-muted">
                        Search Products
                    </label>

                    <input
                        type="text"
                        id="search"
                        class="form-control"
                        placeholder="Search by name, category, color..."
                        autocomplete="off"
                    >

                    <div
                        id="search-suggestions"
                        class="position-absolute w-100 bg-white border rounded shadow-sm mt-1 d-none"
                        style="
                            z-index: 1000;
                            max-height: 250px;
                            overflow-y: auto;
                        "
                    ></div>

                </div>


                {{-- CATEGORY --}}
                <div class="col-md-2">

                    <label class="form-label fw-bold small text-muted">
                        Category
                    </label>

                    <select
                        id="category-filter"
                        class="form-select"
                    >

                        <option value="">
                            All Categories
                        </option>

                        @foreach($categories as $category)

                            <option value="{{ $category }}">
                                {{ $category }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- PRICE RANGE --}}
                <div class="col-md-3">

                    <label class="form-label fw-bold small text-muted">
                        Price Range (₹)
                    </label>

                    <div class="input-group">

                        <input
                            type="number"
                            id="price-min"
                            class="form-control"
                            placeholder="Min"
                            min="0"
                        >

                        <span class="input-group-text">
                            -
                        </span>

                        <input
                            type="number"
                            id="price-max"
                            class="form-control"
                            placeholder="Max"
                            min="0"
                        >

                    </div>

                </div>


                {{-- SORT --}}
                <div class="col-md-3">

                    <label class="form-label fw-bold small text-muted">
                        Sort By
                    </label>

                    <select
                        id="sort"
                        class="form-select"
                    >

                        <option value="">
                            Default
                        </option>

                        <option value="price-asc">
                            Price: Low → High
                        </option>

                        <option value="price-desc">
                            Price: High → Low
                        </option>

                    </select>

                </div>

            </div>


            {{-- SECOND FILTER ROW --}}
            <div class="row g-3 mt-1 align-items-end">

                {{-- STOCK FILTER --}}
                <div class="col-md-3">

                    <label class="form-label fw-bold small text-muted">
                        Stock Availability
                    </label>

                    <select
                        id="stock-status"
                        class="form-select"
                    >

                        <option value="">
                            All Stock
                        </option>

                        <option value="in-stock">
                            ✅ In Stock
                        </option>

                        <option value="low-stock">
                            ⚠️ Low Stock
                        </option>

                        <option value="out-stock">
                            ❌ Out of Stock
                        </option>

                    </select>

                </div>


                {{-- FILTER BUTTONS --}}
                <div class="col-md-9 text-end">

                    <button
                        id="filter-btn"
                        class="btn btn-primary"
                    >
                        🔍 Apply Filters
                    </button>

                    <button
                        id="clear-filter-btn"
                        class="btn btn-outline-secondary ms-2"
                    >
                        ✖ Clear
                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- SEARCH HISTORY --}}
    @if(!empty($searchHistory))

        <div class="mb-3 d-flex align-items-center flex-wrap gap-2">

            <span class="text-muted small fw-bold">
                Recent Searches:
            </span>

            @foreach(array_slice($searchHistory, 0, 8) as $historyItem)

                <button
                    class="btn btn-sm btn-outline-secondary search-history-chip"
                    data-keyword="{{ $historyItem }}"
                >
                    {{ $historyItem }}
                </button>

            @endforeach

            <button
                class="btn btn-sm btn-link text-danger p-0 clear-history-btn"
            >
                Clear History
            </button>

        </div>

    @endif


    {{-- RESULT COUNT + LOADING --}}
    <div class="d-flex justify-content-between align-items-center mb-2">

        <div id="result-count" class="text-muted small fw-semibold">

            {{ $resultCount }}
            {{ $resultCount == 1 ? 'product' : 'products' }}
            found

        </div>


        <div
            id="loading-indicator"
            class="text-primary small d-none"
        >
            <span
                class="spinner-border spinner-border-sm me-1"
                role="status"
            ></span>

            Loading products...
        </div>

    </div>


    {{-- PRODUCT TABLE --}}
    <div class="card shadow-sm border-0">

        <div class="card-body p-0">

            <div
                class="table-responsive"
                id="product-table-wrapper"
            >

                <table class="table table-hover mb-0 align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>Name</th>

                            <th width="20%">
                                Details
                            </th>

                            <th>
                                Image
                            </th>

                            <th>
                                Size
                            </th>

                            <th>
                                Color
                            </th>

                            <th>
                                Category
                            </th>

                            <th>
                                Price (₹)
                            </th>

                            <th>
                                Stock
                            </th>

                            <th class="text-center">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($products as $product)

                            <tr>

                                <td class="fw-semibold">
                                    {{ $product->name }}
                                </td>


                                <td style="white-space: normal;">
                                    {{ Str::limit($product->details, 60) }}
                                </td>


                                <td>

                                    @if($product->image)

                                        <img
                                            src="{{ asset($product->image) }}"
                                            width="70"
                                            class="rounded shadow-sm border"
                                        >

                                    @else

                                        <span class="text-muted">
                                            No Image
                                        </span>

                                    @endif

                                </td>


                                <td>
                                    {{ $product->size }}
                                </td>


                                <td>
                                    {{ $product->color }}
                                </td>


                                <td>
                                    {{ $product->category }}
                                </td>


                                <td class="fw-bold text-success">

                                    ₹{{ number_format($product->price) }}

                                </td>


                                <td>

                                    @if($product->stock <= 0)

                                        <span class="badge bg-danger">
                                            Out of Stock
                                        </span>

                                    @elseif(
                                        $product->stock <=
                                        ($product->low_stock_threshold ?? 5)
                                    )

                                        <span class="badge bg-warning text-dark">
                                            Low ({{ $product->stock }})
                                        </span>

                                    @else

                                        <span class="badge bg-success">
                                            {{ $product->stock }}
                                        </span>

                                    @endif

                                </td>


                                <td class="text-center">

                                    <a
                                        href="{{ route('products.edit', $product) }}"
                                        class="btn btn-warning btn-sm me-1"
                                    >
                                        ✏ Edit
                                    </a>


                                    <form
                                        action="{{ route('products.destroy', $product) }}"
                                        method="POST"
                                        class="d-inline"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Delete this product?')"
                                        >
                                            🗑 Delete
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center py-4 text-muted"
                                >
                                    No products found.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>


                {{-- PAGINATION --}}
                <div class="mt-3 px-3 pb-3">

                    {{ $products->links() }}

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | Fetch Products
    |--------------------------------------------------------------------------
    */
    function fetch_data(page = 1) {

        const keyword = $('#search').val();

        const category = $('#category-filter').val();

        const priceMin = $('#price-min').val();

        const priceMax = $('#price-max').val();

        const sort = $('#sort').val();

        const stockStatus = $('#stock-status').val();


        /*
        |--------------------------------------------------------------------------
        | Show Loading
        |--------------------------------------------------------------------------
        */
        $('#loading-indicator').removeClass('d-none');


        $.ajax({

            url: "{{ route('products.index') }}",

            type: "GET",

            data: {
                page: page,
                keyword: keyword,
                category: category,
                price_min: priceMin,
                price_max: priceMax,
                sort: sort,
                stock_status: stockStatus
            },

            success: function (data) {

                /*
                |--------------------------------------------------------------------------
                | Replace Product Table
                |--------------------------------------------------------------------------
                */
                $('#product-table-wrapper').html(
                    $(data).find('#product-table-wrapper').html()
                );


                /*
                |--------------------------------------------------------------------------
                | Update Result Count
                |--------------------------------------------------------------------------
                */
                const newCount = $(data)
                    .find('#result-count')
                    .text()
                    .trim();

                $('#result-count').text(newCount);


                /*
                |--------------------------------------------------------------------------
                | Rebind Pagination
                |--------------------------------------------------------------------------
                */
                bindPagination();


                /*
                |--------------------------------------------------------------------------
                | Rebind Search History
                |--------------------------------------------------------------------------
                */
                bindHistory();

            },

            error: function () {

                alert(
                    'Unable to load products. Please try again.'
                );

            },

            complete: function () {

                $('#loading-indicator')
                    .addClass('d-none');

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */
    function bindPagination() {

        $(document)
            .off('click.productPagination', '.pagination a')
            .on(
                'click.productPagination',
                '.pagination a',
                function (e) {

                    e.preventDefault();

                    const url = new URL(
                        $(this).attr('href'),
                        window.location.origin
                    );

                    const page =
                        url.searchParams.get('page') || 1;

                    fetch_data(page);

                }
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Search History
    |--------------------------------------------------------------------------
    */
    function bindHistory() {

        $('.search-history-chip')
            .off('click')
            .on('click', function () {

                $('#search').val(
                    $(this).data('keyword')
                );

                fetch_data(1);

            });


        $('.clear-history-btn')
            .off('click')
            .on('click', function () {

                $.get(
                    "{{ route('products.index') }}",
                    {
                        clear_history: 1
                    }
                );

                $('.clear-history-btn')
                    .closest('.mb-3')
                    .remove();

            });

    }


    /*
    |--------------------------------------------------------------------------
    | Live Search
    |--------------------------------------------------------------------------
    */
    $('#search').on('keyup', function () {

        const keyword = $(this).val();

        const suggestionsContainer =
            $('#search-suggestions');


        /*
        |--------------------------------------------------------------------------
        | Suggestions
        |--------------------------------------------------------------------------
        */
        if (keyword.length >= 2) {

            $.ajax({

                url:
                    "{{ route('products.suggestions') }}",

                type: "GET",

                data: {
                    keyword: keyword
                },

                success: function (data) {

                    if (data.length > 0) {

                        let html = '';

                        data.forEach(function (item) {

                            html += `
                                <div
                                    class="suggestion-item p-2 border-bottom"
                                    data-keyword="${item.label}"
                                    style="cursor:pointer;"
                                >
                                    <div class="d-flex justify-content-between">

                                        <span>
                                            ${item.label}
                                        </span>

                                        <span class="badge bg-secondary small">
                                            ${item.type}
                                        </span>

                                    </div>
                                </div>
                            `;

                        });

                        suggestionsContainer
                            .html(html)
                            .removeClass('d-none');

                    } else {

                        suggestionsContainer
                            .addClass('d-none')
                            .empty();

                    }

                }

            });

        } else {

            suggestionsContainer
                .addClass('d-none')
                .empty();

        }


        /*
        |--------------------------------------------------------------------------
        | AJAX Search
        |--------------------------------------------------------------------------
        */
        fetch_data(1);

    });


    /*
    |--------------------------------------------------------------------------
    | Suggestion Click
    |--------------------------------------------------------------------------
    */
    $(document).on(
        'click',
        '.suggestion-item',
        function () {

            $('#search').val(
                $(this).data('keyword')
            );

            $('#search-suggestions')
                .addClass('d-none')
                .empty();

            fetch_data(1);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Close Suggestions
    |--------------------------------------------------------------------------
    */
    $(document).on('click', function (e) {

        if (
            !$(e.target).closest(
                '#search-suggestions, #search'
            ).length
        ) {

            $('#search-suggestions')
                .addClass('d-none')
                .empty();

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Category
    |--------------------------------------------------------------------------
    */
    $('#category-filter').on(
        'change',
        function () {

            fetch_data(1);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Price Filter
    |--------------------------------------------------------------------------
    */
    $('#price-min, #price-max').on(
        'change',
        function () {

            fetch_data(1);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Sort
    |--------------------------------------------------------------------------
    */
    $('#sort').on(
        'change',
        function () {

            fetch_data(1);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | NEW: Stock Filter
    |--------------------------------------------------------------------------
    */
    $('#stock-status').on(
        'change',
        function () {

            fetch_data(1);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Apply Filters
    |--------------------------------------------------------------------------
    */
    $('#filter-btn').on(
        'click',
        function () {

            fetch_data(1);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Clear Filters
    |--------------------------------------------------------------------------
    */
    $('#clear-filter-btn').on(
        'click',
        function () {

            $('#search').val('');

            $('#category-filter').val('');

            $('#price-min').val('');

            $('#price-max').val('');

            $('#sort').val('');

            $('#stock-status').val('');

            $('#search-suggestions')
                .addClass('d-none')
                .empty();

            fetch_data(1);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Initial Bindings
    |--------------------------------------------------------------------------
    */
    bindPagination();

    bindHistory();

});

</script>

@endpush