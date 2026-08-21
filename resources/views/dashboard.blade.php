@extends('layouts.admin')

@section('content')

<div class="py-4">

    {{-- ========================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ========================================================= --}}

    <div class="mb-4">

        <h2 class="fw-bold">
            📊 Dashboard Overview
        </h2>

        <p class="text-muted">
            Welcome back! Here's what's happening with your store.
        </p>

    </div>


    {{-- ========================================================= --}}
    {{-- STATISTICS CARDS --}}
    {{-- ========================================================= --}}

    <div class="row g-4 mb-4">

        {{-- TOTAL --}}
        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100 border-start border-4 border-primary">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1 small text-uppercase fw-bold">
                                Total Products
                            </p>

                            <h3 class="fw-bold mb-0">
                                {{ $totalProducts }}
                            </h3>

                        </div>

                        <div class="fs-1 text-primary opacity-25">
                            📦
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ACTIVE --}}
        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100 border-start border-4 border-success">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1 small text-uppercase fw-bold">
                                Active Products
                            </p>

                            <h3 class="fw-bold mb-0">
                                {{ $activeProducts }}
                            </h3>

                        </div>

                        <div class="fs-1 text-success opacity-25">
                            ✅
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- OUT OF STOCK --}}
        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100 border-start border-4 border-danger">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1 small text-uppercase fw-bold">
                                Out of Stock
                            </p>

                            <h3 class="fw-bold mb-0">
                                {{ $outOfStockProducts }}
                            </h3>

                        </div>

                        <div class="fs-1 text-danger opacity-25">
                            ⚠️
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- LOW STOCK --}}
        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100 border-start border-4 border-warning">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1 small text-uppercase fw-bold">
                                Low Stock
                            </p>

                            <h3 class="fw-bold mb-0">
                                {{ $lowStockProducts }}
                            </h3>

                        </div>

                        <div class="fs-1 text-warning opacity-25">
                            📉
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- LOW STOCK ALERT --}}
    {{-- ========================================================= --}}

    @if($lowStockProducts > 0)

        <div
            class="alert alert-warning shadow-sm d-flex align-items-center mb-4"
            role="alert"
        >

            <span class="fs-4 me-2">
                ⚠️
            </span>

            <div>

                <strong>
                    Low Stock Alert!
                </strong>

                {{ $lowStockProducts }}
                product(s) have low stock.

                <a
                    href="{{ route('products.index') }}"
                    class="alert-link"
                >
                    View Products
                </a>

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- CHARTS --}}
    {{-- ========================================================= --}}

    <div class="row g-4 mb-4">

        {{-- CATEGORY CHART --}}
        <div class="col-md-6">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="mb-0 fw-bold">
                        📊 Category-wise Products
                    </h5>

                </div>

                <div class="card-body">

                    @if($categoryStats->count() > 0)

                        <div style="height:300px;">

                            <canvas id="categoryChart"></canvas>

                        </div>

                    @else

                        <p class="text-muted text-center py-4">
                            No category data available yet.
                        </p>

                    @endif

                </div>

            </div>

        </div>


        {{-- MONTHLY CHART --}}
        <div class="col-md-6">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="mb-0 fw-bold">
                        📈 Products Added (Last 6 Months)
                    </h5>

                </div>

                <div class="card-body">

                    @if($productsByMonth->count() > 0)

                        <div style="height:300px;">

                            <canvas id="productsChart"></canvas>

                        </div>

                    @else

                        <p class="text-muted text-center py-4">
                            No products data available yet.
                        </p>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- PRODUCT SEARCH / FILTER --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-1 fw-bold">
                        📦 Product Management
                    </h5>

                    <small class="text-muted">
                        Search, filter and manage products
                    </small>

                </div>

                <a
                    href="{{ route('products.index') }}"
                    class="btn btn-sm btn-outline-primary"
                >
                    View All Products
                </a>

            </div>

        </div>


        <div class="card-body">

            {{-- ================================================= --}}
            {{-- FILTERS --}}
            {{-- ================================================= --}}

            <div class="row g-3">

                {{-- SEARCH --}}
                <div class="col-md-4">

                    <label class="form-label fw-bold small">
                        Search
                    </label>

                    <input
                        type="text"
                        id="dashboard-search"
                        class="form-control"
                        placeholder="🔍 Search product..."
                        autocomplete="off"
                    >

                </div>


                {{-- CATEGORY --}}
                <div class="col-md-2">

                    <label class="form-label fw-bold small">
                        Category
                    </label>

                    <select
                        id="dashboard-category"
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


                {{-- STATUS --}}
                <div class="col-md-2">

                    <label class="form-label fw-bold small">
                        Status
                    </label>

                    <select
                        id="dashboard-status"
                        class="form-select"
                    >

                        <option value="">
                            All Status
                        </option>

                        <option value="active">
                            Active
                        </option>

                        <option value="inactive">
                            Inactive
                        </option>

                    </select>

                </div>


                {{-- STOCK --}}
                <div class="col-md-2">

                    <label class="form-label fw-bold small">
                        Stock
                    </label>

                    <select
                        id="dashboard-stock"
                        class="form-select"
                    >

                        <option value="">
                            All Stock
                        </option>

                        <option value="in-stock">
                            In Stock
                        </option>

                        <option value="low-stock">
                            Low Stock
                        </option>

                        <option value="out-stock">
                            Out of Stock
                        </option>

                    </select>

                </div>


                {{-- SORT --}}
                <div class="col-md-2">

                    <label class="form-label fw-bold small">
                        Sort
                    </label>

                    <select
                        id="dashboard-sort"
                        class="form-select"
                    >

                        <option value="">
                            Latest
                        </option>

                        <option value="price-asc">
                            Price ↑
                        </option>

                        <option value="price-desc">
                            Price ↓
                        </option>

                        <option value="name-asc">
                            Name A-Z
                        </option>

                        <option value="name-desc">
                            Name Z-A
                        </option>

                        <option value="oldest">
                            Oldest
                        </option>

                    </select>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- LOADING + RESULT COUNT --}}
            {{-- ================================================= --}}

            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">

                <div
                    id="dashboard-result-count"
                    class="small text-muted fw-semibold"
                >

                    {{ $dashboardProducts->total() }}
                    {{ $dashboardProducts->total() == 1 ? 'product' : 'products' }}
                    found

                </div>


                <div
                    id="dashboard-loading"
                    class="text-primary small d-none"
                >

                    <span
                        class="spinner-border spinner-border-sm me-1"
                    ></span>

                    Loading...

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- PRODUCT TABLE --}}
            {{-- ================================================= --}}

            <div id="dashboard-products-section">

                <div class="table-responsive">

                    <table class="table table-hover mb-0 align-middle">

                        <thead class="table-dark">

                            <tr>

                                <th>
                                    Name
                                </th>

                                <th>
                                    Category
                                </th>

                                <th>
                                    Price
                                </th>

                                <th>
                                    Stock
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Added On
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($dashboardProducts as $product)

                                <tr>

                                    {{-- NAME --}}
                                    <td class="fw-semibold">

                                        {{ $product->name }}

                                    </td>


                                    {{-- CATEGORY --}}
                                    <td>

                                        {{ $product->category ?: 'Uncategorized' }}

                                    </td>


                                    {{-- PRICE --}}
                                    <td class="fw-bold text-success">

                                        ₹{{ number_format($product->price, 2) }}

                                    </td>


                                    {{-- STOCK --}}
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

                                                Low
                                                ({{ $product->stock }})

                                            </span>

                                        @else

                                            <span class="badge bg-success">

                                                {{ $product->stock }}

                                            </span>

                                        @endif

                                    </td>


                                    {{-- STATUS --}}
                                    <td>

                                        @if($product->status === 'active')

                                            <span class="badge bg-success">

                                                Active

                                            </span>

                                        @else

                                            <span class="badge bg-secondary">

                                                Inactive

                                            </span>

                                        @endif

                                    </td>


                                    {{-- DATE --}}
                                    <td>

                                        {{ $product->created_at->format('d M Y') }}

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="6"
                                        class="text-center py-5 text-muted"
                                    >

                                        <div class="fs-1">
                                            📦
                                        </div>

                                        <h5 class="mt-2">
                                            No products found
                                        </h5>

                                        <p class="mb-0">
                                            Try changing your search or filters.
                                        </p>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- ================================================= --}}
                {{-- PAGINATION --}}
                {{-- ================================================= --}}

                @if($dashboardProducts->hasPages())

                    <div class="d-flex justify-content-center mt-4">

                        {{ $dashboardProducts->links() }}

                    </div>

                @endif

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
    | Chart Data
    |--------------------------------------------------------------------------
    */

    const categoryData = @json($categoryStats);

    const productsData = @json($productsByMonth);


    /*
    |--------------------------------------------------------------------------
    | Category Chart
    |--------------------------------------------------------------------------
    */

    @if($categoryStats->count() > 0)

        new Chart(
            document.getElementById('categoryChart'),
            {

                type: 'doughnut',

                data: {

                    labels: categoryData.map(
                        item => item.category || 'Uncategorized'
                    ),

                    datasets: [{

                        data: categoryData.map(
                            item => item.count
                        ),

                        backgroundColor: [
                            '#4F46E5',
                            '#7C3AED',
                            '#EC4899',
                            '#F59E0B',
                            '#10B981',
                            '#3B82F6',
                            '#EF4444',
                            '#8B5CF6'
                        ],

                        borderWidth: 0

                    }]

                },

                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    plugins: {

                        legend: {

                            position: 'bottom',

                            labels: {

                                padding: 15,

                                usePointStyle: true

                            }

                        }

                    }

                }

            }
        );

    @endif


    /*
    |--------------------------------------------------------------------------
    | Products Monthly Chart
    |--------------------------------------------------------------------------
    */

    @if($productsByMonth->count() > 0)

        const productLabels = productsData.map(
            item => `${item.month}/${item.year}`
        );

        const productValues = productsData.map(
            item => item.total
        );


        new Chart(
            document.getElementById('productsChart'),
            {

                type: 'line',

                data: {

                    labels: productLabels,

                    datasets: [{

                        label: 'Products Added',

                        data: productValues,

                        borderColor: '#10B981',

                        backgroundColor:
                            'rgba(16, 185, 129, 0.1)',

                        fill: true,

                        tension: 0.4,

                        pointRadius: 5,

                        pointBackgroundColor: '#10B981'

                    }]

                },

                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    scales: {

                        y: {

                            beginAtZero: true,

                            ticks: {

                                stepSize: 1,

                                callback: function(value) {

                                    return Math.round(value);

                                }

                            }

                        }

                    },

                    plugins: {

                        legend: {

                            display: false

                        }

                    }

                }

            }
        );

    @endif


    /*
    |--------------------------------------------------------------------------
    | AJAX Product Search / Filter
    |--------------------------------------------------------------------------
    */

    let searchTimer;


    function fetchDashboardProducts(page = 1) {

        const keyword =
            $('#dashboard-search').val();

        const category =
            $('#dashboard-category').val();

        const status =
            $('#dashboard-status').val();

        const stockStatus =
            $('#dashboard-stock').val();

        const sort =
            $('#dashboard-sort').val();


        /*
        |--------------------------------------------------------------------------
        | Loading
        |--------------------------------------------------------------------------
        */

        $('#dashboard-loading')
            .removeClass('d-none');


        /*
        |--------------------------------------------------------------------------
        | AJAX
        |--------------------------------------------------------------------------
        */

        $.ajax({

            url: "{{ route('dashboard') }}",

            type: "GET",

            data: {

                page: page,

                keyword: keyword,

                category: category,

                status: status,

                stock_status: stockStatus,

                sort: sort

            },

            success: function(response) {

                /*
                |--------------------------------------------------------------------------
                | Extract product section
                |--------------------------------------------------------------------------
                */

                const newSection =
                    $(response)
                        .find('#dashboard-products-section')
                        .html();


                /*
                |--------------------------------------------------------------------------
                | Replace product table
                |--------------------------------------------------------------------------
                */

                $('#dashboard-products-section')
                    .html(newSection);


                /*
                |--------------------------------------------------------------------------
                | Update result count
                |--------------------------------------------------------------------------
                */

                const resultCount =
                    $(response)
                        .find('#dashboard-result-count')
                        .text()
                        .trim();


                $('#dashboard-result-count')
                    .text(resultCount);


                /*
                |--------------------------------------------------------------------------
                | Update browser URL
                |--------------------------------------------------------------------------
                */

                const url =
                    new URL(
                        window.location.href
                    );


                url.searchParams.delete('page');


                if (keyword) {

                    url.searchParams.set(
                        'keyword',
                        keyword
                    );

                } else {

                    url.searchParams.delete(
                        'keyword'
                    );

                }


                if (category) {

                    url.searchParams.set(
                        'category',
                        category
                    );

                } else {

                    url.searchParams.delete(
                        'category'
                    );

                }


                if (status) {

                    url.searchParams.set(
                        'status',
                        status
                    );

                } else {

                    url.searchParams.delete(
                        'status'
                    );

                }


                if (stockStatus) {

                    url.searchParams.set(
                        'stock_status',
                        stockStatus
                    );

                } else {

                    url.searchParams.delete(
                        'stock_status'
                    );

                }


                if (sort) {

                    url.searchParams.set(
                        'sort',
                        sort
                    );

                } else {

                    url.searchParams.delete(
                        'sort'
                    );

                }


                if (page > 1) {

                    url.searchParams.set(
                        'page',
                        page
                    );

                }


                window.history.replaceState(
                    {},
                    '',
                    url
                );

            },

            error: function(xhr) {

                console.error(xhr);

                alert(
                    'Unable to load products. Please try again.'
                );

            },

            complete: function() {

                $('#dashboard-loading')
                    .addClass('d-none');

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | LIVE SEARCH
    |--------------------------------------------------------------------------
    */

    $('#dashboard-search').on(
        'input',
        function() {

            clearTimeout(searchTimer);


            searchTimer = setTimeout(
                function() {

                    fetchDashboardProducts(1);

                },
                300
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | CATEGORY FILTER
    |--------------------------------------------------------------------------
    */

    $('#dashboard-category').on(
        'change',
        function() {

            fetchDashboardProducts(1);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | STATUS FILTER
    |--------------------------------------------------------------------------
    */

    $('#dashboard-status').on(
        'change',
        function() {

            fetchDashboardProducts(1);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | STOCK FILTER
    |--------------------------------------------------------------------------
    */

    $('#dashboard-stock').on(
        'change',
        function() {

            fetchDashboardProducts(1);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | SORT
    |--------------------------------------------------------------------------
    */

    $('#dashboard-sort').on(
        'change',
        function() {

            fetchDashboardProducts(1);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | AJAX PAGINATION
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '#dashboard-products-section .pagination a',
        function(e) {

            e.preventDefault();


            const href =
                $(this).attr('href');


            if (!href) {
                return;
            }


            const url =
                new URL(
                    href,
                    window.location.origin
                );


            const page =
                url.searchParams.get('page') || 1;


            fetchDashboardProducts(page);


            /*
            |--------------------------------------------------------------------------
            | Scroll back to product table
            |--------------------------------------------------------------------------
            */

            $('html, body').animate({

                scrollTop:
                    $('#dashboard-products-section')
                        .offset()
                        .top - 100

            }, 300);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Prevent Form-Like Enter Reload
    |--------------------------------------------------------------------------
    */

    $('#dashboard-search').on(
        'keydown',
        function(e) {

            if (e.key === 'Enter') {

                e.preventDefault();

                fetchDashboardProducts(1);

            }

        }
    );

});

</script>

@endpush