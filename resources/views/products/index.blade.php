@extends('layouts.admin')

@section('content')

<style>
    :root {
        --product-primary: #4f46e5;
        --product-primary-dark: #4338ca;
        --product-success: #16a34a;
        --product-warning: #d97706;
        --product-danger: #dc2626;
        --product-info: #0891b2;
        --product-dark: #111827;
        --product-muted: #64748b;
        --product-border: #e5e7eb;
        --product-bg: #f8fafc;
        --product-card: #ffffff;
    }

    body {
        background: #f6f8fc;
    }

    .products-page {
        padding: 10px 0 40px;
    }

    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .product-header {
        background: linear-gradient(135deg,
                #ffffff 0%,
                #f8faff 100%);
        border: 1px solid var(--product-border);
        border-radius: 20px;
        padding: 24px;
        margin-bottom: 22px;
        box-shadow: 0 8px 30px rgba(15, 23, 42, .05);
    }

    .product-title-wrapper {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .product-page-icon {
        width: 54px;
        height: 54px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg,
                var(--product-primary),
                #7c3aed);
        color: #fff;
        font-size: 24px;
        box-shadow: 0 8px 20px rgba(79, 70, 229, .25);
    }

    .product-title {
        font-size: 25px;
        font-weight: 800;
        color: var(--product-dark);
        margin: 0;
        letter-spacing: -.4px;
    }

    .product-subtitle {
        color: var(--product-muted);
        margin: 4px 0 0;
        font-size: 14px;
    }

    /* =========================================================
       BUTTONS
    ========================================================= */

    .modern-btn {
        border-radius: 11px;
        font-weight: 700;
        padding: 10px 15px;
        border: 1px solid transparent;
        transition: all .2s ease;
    }

    .modern-btn:hover {
        transform: translateY(-1px);
    }

    .btn-modern-primary {
        background: linear-gradient(135deg,
                var(--product-primary),
                var(--product-primary-dark));
        color: #fff;
        box-shadow: 0 6px 15px rgba(79, 70, 229, .2);
    }

    .btn-modern-primary:hover {
        color: #fff;
        box-shadow: 0 9px 20px rgba(79, 70, 229, .3);
    }

    .btn-modern-success {
        background: #ecfdf3;
        color: #15803d;
        border-color: #bbf7d0;
    }

    .btn-modern-success:hover {
        background: #dcfce7;
        color: #166534;
    }

    .btn-modern-danger {
        background: #fff1f2;
        color: #dc2626;
        border-color: #fecdd3;
    }

    .btn-modern-danger:hover {
        background: #ffe4e6;
        color: #b91c1c;
    }

    /* =========================================================
       STAT CARDS
    ========================================================= */

    .mini-stat-card {
        background: #fff;
        border: 1px solid var(--product-border);
        border-radius: 17px;
        padding: 18px;
        height: 100%;
        box-shadow: 0 5px 20px rgba(15, 23, 42, .04);
        transition: all .2s ease;
    }

    .mini-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(15, 23, 42, .08);
    }

    .mini-stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        margin-bottom: 12px;
    }

    .icon-blue {
        background: #eef2ff;
        color: #4f46e5;
    }

    .icon-green {
        background: #ecfdf5;
        color: #16a34a;
    }

    .icon-orange {
        background: #fff7ed;
        color: #ea580c;
    }

    .icon-red {
        background: #fef2f2;
        color: #dc2626;
    }

    .mini-stat-label {
        font-size: 12px;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .mini-stat-value {
        font-size: 25px;
        font-weight: 800;
        color: #111827;
        margin-top: 2px;
    }

    /* =========================================================
       FILTER CARD
    ========================================================= */

    .filter-card {
        background: #fff;
        border: 1px solid var(--product-border);
        border-radius: 18px;
        box-shadow: 0 7px 25px rgba(15, 23, 42, .045);
        overflow: visible;
    }

    .filter-header {
        padding: 17px 20px;
        border-bottom: 1px solid #eef0f4;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .filter-title {
        font-weight: 800;
        color: #111827;
        margin: 0;
        font-size: 15px;
    }

    .filter-description {
        font-size: 12px;
        color: #94a3b8;
        margin: 3px 0 0;
    }

    .filter-body {
        padding: 20px;
    }

    .modern-label {
        font-size: 12px;
        font-weight: 800;
        color: #475569;
        margin-bottom: 7px;
    }

    .modern-input,
    .modern-select {
        height: 43px;
        border: 1px solid #dbe1e8;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        color: #1e293b;
        transition: all .2s ease;
        background-color: #fff;
    }

    .modern-input:focus,
    .modern-select:focus {
        border-color: var(--product-primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, .10);
    }

    .search-wrapper {
        position: relative;
    }

    .search-icon {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        z-index: 2;
    }

    .search-input {
        padding-left: 38px !important;
    }

    .suggestions-box {
        position: absolute;
        width: 100%;
        top: calc(100% + 6px);
        left: 0;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 13px;
        box-shadow: 0 18px 45px rgba(15, 23, 42, .15);
        z-index: 1050;
        overflow: hidden;
    }

    .suggestion-item {
        padding: 12px 14px;
        border-bottom: 1px solid #f1f5f9;
        transition: background .15s ease;
    }

    .suggestion-item:hover {
        background: #f8fafc;
    }

    .suggestion-item:last-child {
        border-bottom: 0;
    }

    .suggestion-type {
        font-size: 10px;
        text-transform: uppercase;
        font-weight: 800;
        letter-spacing: .4px;
    }

    /* =========================================================
       FILTER BUTTONS
    ========================================================= */

    .filter-actions {
        border-top: 1px solid #f1f5f9;
        margin-top: 20px;
        padding-top: 17px;
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    /* =========================================================
       SEARCH HISTORY
    ========================================================= */

    .history-card {
        background: #fff;
        border: 1px solid var(--product-border);
        border-radius: 14px;
        padding: 12px 15px;
        box-shadow: 0 4px 15px rgba(15, 23, 42, .035);
    }

    .history-chip {
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 11px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
    }

    .history-chip:hover {
        background: #eef2ff;
        border-color: #c7d2fe;
        color: #4338ca;
    }

    /* =========================================================
       BULK ACTION
    ========================================================= */

    .bulk-bar {
        border: 1px solid #c7d2fe;
        background: linear-gradient(135deg,
                #eef2ff,
                #f5f3ff);
        border-radius: 15px;
        padding: 14px 17px;
        box-shadow: 0 6px 20px rgba(79, 70, 229, .08);
    }

    .selected-number {
        width: 30px;
        height: 30px;
        border-radius: 9px;
        background: var(--product-primary);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        margin-right: 8px;
    }

    /* =========================================================
       RESULTS BAR
    ========================================================= */

    .results-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 16px 2px 10px;
    }

    .result-count {
        font-size: 13px;
        color: #64748b;
        font-weight: 700;
    }

    .loading-indicator {
        background: #eef2ff;
        color: #4f46e5;
        padding: 7px 12px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
    }

    /* =========================================================
       TABLE
    ========================================================= */

    .product-table-card {
        border: 1px solid var(--product-border);
        border-radius: 18px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 8px 28px rgba(15, 23, 42, .05);
    }

    .table-container {
        overflow-x: auto;
    }

    .modern-product-table {
        min-width: 1450px;
        margin: 0;
    }

    .modern-product-table thead th {
        background: #111827;
        color: #e5e7eb;
        border: 0;
        padding: 14px 12px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .5px;
        white-space: nowrap;
    }

    .modern-product-table tbody td {
        padding: 14px 12px;
        border-color: #eef2f7;
        font-size: 13px;
        color: #334155;
        vertical-align: middle;
    }

    .modern-product-table tbody tr {
        transition: background .15s ease;
    }

    .modern-product-table tbody tr:hover {
        background: #f8fafc;
    }

    .product-id {
        font-size: 12px;
        font-weight: 800;
        color: #64748b;
    }

    .product-name {
        font-size: 14px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 3px;
    }

    .product-details {
        max-width: 230px;
        color: #64748b;
        line-height: 1.5;
    }

    .product-image {
        width: 58px;
        height: 58px;
        object-fit: cover;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .no-image {
        width: 58px;
        height: 58px;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
    }

    .category-badge {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        padding: 5px 9px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .price-value {
        color: #15803d;
        font-weight: 800;
        white-space: nowrap;
    }

    .stock-badge {
        padding: 6px 9px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    .stock-out {
        background: #fef2f2;
        color: #dc2626;
    }

    .stock-low {
        background: #fff7ed;
        color: #c2410c;
    }

    .stock-good {
        background: #ecfdf5;
        color: #15803d;
    }

    .status-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .modern-switch {
        width: 2.5em !important;
        height: 1.35em;
        cursor: pointer;
    }

    .modern-switch:checked {
        background-color: #16a34a;
        border-color: #16a34a;
    }

    .status-badge {
        font-size: 10px;
        font-weight: 800;
        padding: 5px 8px;
        border-radius: 6px;
    }

    /* =========================================================
       ACTION BUTTONS
    ========================================================= */

    .action-btn {
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        padding: 6px 9px;
        margin: 2px;
        transition: all .15s ease;
    }

    .action-btn:hover {
        transform: translateY(-1px);
    }

    .action-view {
        background: #ecfeff;
        color: #0891b2;
        border: 1px solid #a5f3fc;
    }

    .action-edit {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .action-copy {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
    }

    .action-delete {
        background: #fff1f2;
        color: #dc2626;
        border: 1px solid #fecdd3;
    }

    /* =========================================================
       PAGINATION
    ========================================================= */

    .pagination-wrapper {
        padding: 18px;
        border-top: 1px solid #eef2f7;
        background: #fff;
    }

    .pagination {
        margin: 0;
        justify-content: center;
        gap: 5px;
    }

    .pagination .page-link {
        border: 1px solid #e2e8f0;
        border-radius: 8px !important;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        padding: 7px 11px;
    }

    .pagination .page-item.active .page-link {
        background: var(--product-primary);
        border-color: var(--product-primary);
        color: #fff;
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .empty-state {
        padding: 70px 20px;
        text-align: center;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        margin: auto;
        border-radius: 22px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 35px;
    }

    .empty-title {
        margin-top: 18px;
        font-size: 18px;
        font-weight: 800;
        color: #111827;
    }

    .empty-description {
        color: #64748b;
        font-size: 13px;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 767px) {

        .product-header {
            padding: 18px;
            border-radius: 15px;
        }

        .product-title {
            font-size: 21px;
        }

        .product-page-icon {
            width: 46px;
            height: 46px;
            font-size: 20px;
        }

        .product-header-actions {
            width: 100%;
        }

        .product-header-actions .modern-btn,
        .product-header-actions a {
            flex: 1;
        }

        .filter-body {
            padding: 15px;
        }

        .filter-actions {
            flex-direction: column;
        }

        .filter-actions button {
            width: 100%;
        }

        .results-bar {
            align-items: flex-start;
            gap: 10px;
            flex-direction: column;
        }
    }
</style>


<div class="container-fluid products-page">

    {{-- =========================================================
         PAGE HEADER
    ========================================================= --}}

    <div class="product-header">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

            <div class="product-title-wrapper">

                <div class="product-page-icon">
                    📦
                </div>

                <div>
                    <h2 class="product-title">
                        Products
                    </h2>

                    <p class="product-subtitle">
                        Manage your product catalog, inventory and status.
                    </p>
                </div>

            </div>


            <div class="d-flex gap-2 flex-wrap product-header-actions">

                {{-- CSV --}}
                <button
                    type="button"
                    id="export-csv-btn"
                    class="btn modern-btn btn-modern-success">

                    📊 Export CSV

                </button>


                {{-- TRASH --}}
                <a
                    href="{{ route('products.trash') }}"
                    class="btn modern-btn btn-modern-danger">

                    ♻️ Trash

                </a>


                {{-- CREATE --}}
                <a
                    href="{{ route('products.create') }}"
                    class="btn modern-btn btn-modern-primary">

                    ➕ Add Product

                </a>

            </div>

        </div>

    </div>


    {{-- =========================================================
         ALERTS
    ========================================================= --}}

    @if(session('success'))

    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0">

        <strong>✓ Success:</strong>
        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

    @endif


    @if(session('error'))

    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0">

        <strong>✕ Error:</strong>
        {{ session('error') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

    @endif


    @if($errors->any())

    <div class="alert alert-danger shadow-sm border-0">

        <strong>Please fix the following errors:</strong>

        <ul class="mb-0 mt-2">

            @foreach($errors->all() as $error)

            <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

    @endif


    {{-- =========================================================
         QUICK STATS
    ========================================================= --}}

    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="mini-stat-card">

                <div class="mini-stat-icon icon-blue">
                    📦
                </div>

                <div class="mini-stat-label">
                    Products
                </div>

                <div class="mini-stat-value">
                    {{ $resultCount }}
                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="mini-stat-card">

                <div class="mini-stat-icon icon-green">
                    🟢
                </div>

                <div class="mini-stat-label">
                    Active
                </div>

                <div class="mini-stat-value">
                    {{ $products->where('status', 'active')->count() }}
                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="mini-stat-card">

                <div class="mini-stat-icon icon-orange">
                    ⚠️
                </div>

                <div class="mini-stat-label">
                    Low Stock
                </div>

                <div class="mini-stat-value">

                    {{ $products->filter(function ($product) {
                        return $product->stock > 0 &&
                            $product->stock <= ($product->low_stock_threshold ?? 5);
                    })->count() }}

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="mini-stat-card">

                <div class="mini-stat-icon icon-red">
                    ❌
                </div>

                <div class="mini-stat-label">
                    Out Of Stock
                </div>

                <div class="mini-stat-value">

                    {{ $products->where('stock', '<=', 0)->count() }}

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         FILTER CARD
    ========================================================= --}}

    <div class="filter-card mb-4">

        <div class="filter-header">

            <div>

                <h5 class="filter-title">
                    🔎 Search & Filters
                </h5>

                <p class="filter-description">
                    Find products quickly using advanced filters.
                </p>

            </div>

            <span class="badge rounded-pill bg-light text-dark border">
                {{ $resultCount }} Results
            </span>

        </div>


        <div class="filter-body">

            {{-- FIRST ROW --}}

            <div class="row g-3">

                {{-- SEARCH --}}

                <div class="col-xl-4 col-lg-4 col-md-6">

                    <label class="modern-label">
                        Search Products
                    </label>

                    <div class="search-wrapper">

                        <span class="search-icon">
                            🔍
                        </span>

                        <input
                            type="text"
                            id="search"
                            class="form-control modern-input search-input"
                            placeholder="Name, category, color..."
                            autocomplete="off">

                        <div
                            id="search-suggestions"
                            class="suggestions-box d-none">
                        </div>

                    </div>

                </div>


                {{-- CATEGORY --}}

                <div class="col-xl-2 col-lg-2 col-md-6">

                    <label class="modern-label">
                        Category
                    </label>

                    <select
                        id="category-filter"
                        class="form-select modern-select">

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


                {{-- MIN PRICE --}}

                <div class="col-xl-1 col-lg-2 col-md-6">

                    <label class="modern-label">
                        Min ₹
                    </label>

                    <input
                        type="number"
                        id="price-min"
                        class="form-control modern-input"
                        placeholder="Min"
                        min="0">

                </div>


                {{-- MAX PRICE --}}

                <div class="col-xl-1 col-lg-2 col-md-6">

                    <label class="modern-label">
                        Max ₹
                    </label>

                    <input
                        type="number"
                        id="price-max"
                        class="form-control modern-input"
                        placeholder="Max"
                        min="0">

                </div>


                {{-- STOCK --}}

                <div class="col-xl-2 col-lg-2 col-md-6">

                    <label class="modern-label">
                        Stock
                    </label>

                    <select
                        id="stock-status"
                        class="form-select modern-select">

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


                {{-- SORT --}}

                <div class="col-xl-2 col-lg-2 col-md-6">

                    <label class="modern-label">
                        Sort By
                    </label>

                    <select
                        id="sort"
                        class="form-select modern-select">

                        <option value="">
                            Latest
                        </option>

                        <option value="price-asc">
                            Price: Low → High
                        </option>

                        <option value="price-desc">
                            Price: High → Low
                        </option>

                        <option value="name-asc">
                            Name: A → Z
                        </option>

                        <option value="name-desc">
                            Name: Z → A
                        </option>

                        <option value="oldest">
                            Oldest
                        </option>

                    </select>

                </div>

            </div>


            {{-- SECOND ROW --}}

            <div class="row g-3 mt-1 align-items-end">

                {{-- STATUS --}}

                <div class="col-xl-3 col-lg-4 col-md-6">

                    <label class="modern-label">
                        Status
                    </label>

                    <select
                        id="status-filter"
                        class="form-select modern-select">

                        <option value="">
                            All Status
                        </option>

                        <option value="active">
                            🟢 Active
                        </option>

                        <option value="inactive">
                            ⚪ Inactive
                        </option>

                    </select>

                </div>


                <div class="col-xl-9 col-lg-8 col-md-6">

                    <div class="filter-actions">

                        <button
                            type="button"
                            id="filter-btn"
                            class="btn modern-btn btn-modern-primary">

                            🔍 Apply Filters

                        </button>

                        <button
                            type="button"
                            id="clear-filter-btn"
                            class="btn modern-btn btn-outline-secondary">

                            ✕ Clear

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         SEARCH HISTORY
    ========================================================= --}}

    @if(!empty($searchHistory))

    <div
        id="search-history-wrapper"
        class="history-card mb-3">

        <div class="d-flex align-items-center flex-wrap gap-2">

            <span class="small fw-bold text-muted me-1">
                🕘 Recent Searches
            </span>

            @foreach(array_slice($searchHistory, 0, 8) as $historyItem)

            <button
                type="button"
                class="btn history-chip search-history-chip"
                data-keyword="{{ $historyItem }}">

                {{ $historyItem }}

            </button>

            @endforeach


            <button
                type="button"
                class="btn btn-sm btn-link text-danger clear-history-btn">

                Clear History

            </button>

        </div>

    </div>

    @endif


    {{-- =========================================================
         BULK ACTION BAR
    ========================================================= --}}

    <div
        id="bulk-action-bar"
        class="bulk-bar mb-3 d-none">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

            <div class="d-flex align-items-center">

                <span
                    id="selected-count"
                    class="selected-number">
                    0
                </span>

                <div>

                    <div class="fw-bold text-dark">
                        Products selected
                    </div>

                    <div class="small text-muted">
                        Choose an action below
                    </div>

                </div>

            </div>


            <div>

                <button
                    type="button"
                    id="bulk-delete-btn"
                    class="btn btn-danger btn-sm rounded-3 fw-bold">

                    🗑 Delete Selected

                </button>

                <button
                    type="button"
                    id="clear-selection-btn"
                    class="btn btn-outline-secondary btn-sm rounded-3 fw-bold ms-1">

                    Clear Selection

                </button>

            </div>

        </div>

    </div>


    {{-- =========================================================
         RESULTS
    ========================================================= --}}

    <div class="results-bar">

        <div
            id="result-count"
            class="result-count">

            {{ $resultCount }}
            {{ $resultCount == 1 ? 'product' : 'products' }}
            found

        </div>


        <div
            id="loading-indicator"
            class="loading-indicator d-none">

            <span class="spinner-border spinner-border-sm me-1"></span>

            Loading products...

        </div>

    </div>


    {{-- =========================================================
         PRODUCT TABLE
    ========================================================= --}}

    <div class="product-table-card">

        <div
            id="product-table-wrapper"
            class="table-container">

            <table class="table modern-product-table align-middle">

                <thead>

                    <tr>

                        <th width="45">

                            <input
                                type="checkbox"
                                id="select-all"
                                class="form-check-input"
                                title="Select all">

                        </th>

                        <th>
                            ID
                        </th>

                        <th>
                            Product
                        </th>

                        <th>
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
                            Price
                        </th>

                        <th>
                            Stock
                        </th>

                        <th>
                            Status
                        </th>

                        <th
                            class="text-center"
                            style="min-width:260px;">

                            Actions

                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($products as $product)

                    <tr>

                        {{-- SELECT --}}

                        <td>

                            <input
                                type="checkbox"
                                class="form-check-input product-checkbox"
                                value="{{ $product->id }}">

                        </td>


                        {{-- ID --}}

                        <td>

                            <span class="product-id">
                                #{{ $product->id }}
                            </span>

                        </td>


                        {{-- PRODUCT --}}

                        <td>

                            <div class="product-name">
                                {{ $product->name }}
                            </div>

                            <small class="text-muted">
                                Product
                            </small>

                        </td>


                        {{-- DETAILS --}}

                        <td>

                            <div class="product-details">

                                {{ Str::limit($product->details, 70) }}

                            </div>

                        </td>


                        {{-- IMAGE --}}

                        <td>

                            @if($product->image)

                            <img
                                src="{{ asset($product->image) }}"
                                alt="{{ $product->name }}"
                                class="product-image">

                            @else

                            <div class="no-image">
                                📦
                            </div>

                            @endif

                        </td>


                        {{-- SIZE --}}

                        <td>

                            <span class="badge bg-light text-dark border">
                                {{ $product->size ?: 'N/A' }}
                            </span>

                        </td>


                        {{-- COLOR --}}

                        <td>

                            <span class="badge bg-light text-dark border">
                                {{ $product->color ?: 'N/A' }}
                            </span>

                        </td>


                        {{-- CATEGORY --}}

                        <td>

                            <span class="category-badge">

                                {{ $product->category ?: 'Uncategorized' }}

                            </span>

                        </td>


                        {{-- PRICE --}}

                        <td>

                            <span class="price-value">

                                ₹{{ number_format($product->price, 2) }}

                            </span>

                        </td>


                        {{-- STOCK --}}

                        <td>

                            @if($product->stock <= 0)

                                <span class="stock-badge stock-out">
                                ❌ Out
                                </span>

                                @elseif(
                                $product->stock <=
                                    ($product->low_stock_threshold ?? 5)
                                    )

                                    <span class="stock-badge stock-low">

                                        ⚠️ Low
                                        ({{ $product->stock }})

                                    </span>

                                    @else

                                    <span class="stock-badge stock-good">

                                        ✓ {{ $product->stock }}

                                    </span>

                                    @endif

                        </td>


                        {{-- STATUS --}}

                        <td>

                            <div class="status-wrapper">

                                <input
                                    class="form-check-input modern-switch status-toggle"
                                    type="checkbox"
                                    role="switch"
                                    data-id="{{ $product->id }}"
                                    {{ $product->status === 'active' ? 'checked' : '' }}>

                                <span class="status-label">

                                    @if($product->status === 'active')

                                    <span class="badge bg-success status-badge">
                                        Active
                                    </span>

                                    @else

                                    <span class="badge bg-secondary status-badge">
                                        Inactive
                                    </span>

                                    @endif

                                </span>

                            </div>

                        </td>


                        {{-- ACTIONS --}}

                        <td class="text-center">

                            {{-- VIEW --}}

                            <a
                                href="{{ route('products.show', $product) }}"
                                class="btn action-btn action-view"
                                title="View Details">

                                👁 View

                            </a>


                            {{-- EDIT --}}

                            <a
                                href="{{ route('products.edit', $product) }}"
                                class="btn action-btn action-edit"
                                title="Edit Product">

                                ✏ Edit

                            </a>


                            {{-- DUPLICATE --}}

                            <form
                                action="{{ route('products.duplicate', $product) }}"
                                method="POST"
                                class="d-inline">

                                @csrf

                                <button
                                    type="submit"
                                    class="btn action-btn action-copy"
                                    title="Duplicate Product">

                                    📋 Copy

                                </button>

                            </form>


                            {{-- DELETE --}}

                            <form
                                action="{{ route('products.destroy', $product) }}"
                                method="POST"
                                class="d-inline">

                                @csrf

                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn action-btn action-delete delete-product-btn"
                                    title="Move to Trash">

                                    🗑 Delete

                                </button>

                            </form>

                        </td>

                    </tr>


                    @empty

                    <tr>

                        <td
                            colspan="12"
                            class="p-0">

                            <div class="empty-state">

                                <div class="empty-icon">
                                    📦
                                </div>

                                <div class="empty-title">
                                    No Products Found
                                </div>

                                <p class="empty-description mb-3">
                                    Try changing your search or filters.
                                </p>

                                <button
                                    type="button"
                                    id="empty-clear-btn"
                                    class="btn btn-outline-primary btn-sm rounded-3">

                                    Clear Filters

                                </button>

                            </div>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>


            {{-- PAGINATION --}}

            @if($products->hasPages())

            <div class="pagination-wrapper">

                {{ $products->links() }}

            </div>

            @endif

        </div>

    </div>

</div>


{{-- =============================================================
     BULK DELETE FORM
============================================================= --}}

<form
    id="bulk-delete-form"
    action="{{ route('products.bulk-delete') }}"
    method="POST"
    style="display:none;">

    @csrf

    <div id="bulk-delete-inputs"></div>

</form>


@endsection


@push('scripts')

<script>
    $(document).ready(function() {

        /* =========================================================
           FETCH PRODUCTS
        ========================================================= */

        function fetch_data(page = 1) {

            const keyword =
                $('#search').val();

            const category =
                $('#category-filter').val();

            const priceMin =
                $('#price-min').val();

            const priceMax =
                $('#price-max').val();

            const sort =
                $('#sort').val();

            const stockStatus =
                $('#stock-status').val();

            const status =
                $('#status-filter').val();


            $('#loading-indicator')
                .removeClass('d-none');


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

                    stock_status: stockStatus,

                    status: status

                },


                success: function(data) {

                    const newTable =
                        $(data)
                        .find('#product-table-wrapper')
                        .html();


                    $('#product-table-wrapper')
                        .html(newTable);


                    const newCount =
                        $(data)
                        .find('#result-count')
                        .text()
                        .trim();


                    $('#result-count')
                        .text(newCount);


                    bindPagination();

                    bindStatusToggle();

                    updateBulkSelection();

                },


                error: function(xhr) {

                    console.error(xhr);

                    alert(
                        'Unable to load products. Please try again.'
                    );

                },


                complete: function() {

                    $('#loading-indicator')
                        .addClass('d-none');

                }

            });

        }


        /* =========================================================
           PAGINATION
        ========================================================= */

        function bindPagination() {

            $(document)
                .off(
                    'click.productPagination',
                    '#product-table-wrapper .pagination a'
                )
                .on(
                    'click.productPagination',
                    '#product-table-wrapper .pagination a',
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


                        fetch_data(page);


                        $('html, body').animate({

                            scrollTop: $('#product-table-wrapper')
                                .offset()
                                .top - 100

                        }, 300);

                    }
                );

        }


        /* =========================================================
           STATUS TOGGLE
        ========================================================= */

        function bindStatusToggle() {

            $('.status-toggle')
                .off('change.productStatus')
                .on('change.productStatus', function() {

                    const checkbox =
                        $(this);

                    const productId =
                        checkbox.data('id');

                    const label =
                        checkbox
                        .closest('.status-wrapper')
                        .find('.status-label');


                    const previousState = !checkbox.prop('checked');


                    checkbox.prop(
                        'disabled',
                        true
                    );


                    $.ajax({

                        url: "{{ url('/products') }}/" +
                            productId +
                            "/toggle-status",

                        type: "PATCH",

                        data: {

                            _token: "{{ csrf_token() }}"

                        },


                        success: function(response) {

                            if (
                                response.status === 'active'
                            ) {

                                label.html(
                                    '<span class="badge bg-success status-badge">Active</span>'
                                );

                                checkbox.prop(
                                    'checked',
                                    true
                                );

                            } else {

                                label.html(
                                    '<span class="badge bg-secondary status-badge">Inactive</span>'
                                );

                                checkbox.prop(
                                    'checked',
                                    false
                                );

                            }

                        },


                        error: function(xhr) {

                            checkbox.prop(
                                'checked',
                                previousState
                            );


                            alert(
                                xhr.responseJSON?.message ||
                                'Unable to update product status.'
                            );

                        },


                        complete: function() {

                            checkbox.prop(
                                'disabled',
                                false
                            );

                        }

                    });

                });

        }


        /* =========================================================
           SELECT ALL
        ========================================================= */

        $(document).on(
            'change',
            '#select-all',
            function() {

                const checked =
                    $(this).is(':checked');


                $('.product-checkbox')
                    .prop(
                        'checked',
                        checked
                    );


                updateBulkSelection();

            }
        );


        /* =========================================================
           INDIVIDUAL CHECKBOX
        ========================================================= */

        $(document).on(
            'change',
            '.product-checkbox',
            function() {

                updateBulkSelection();

            }
        );


        /* =========================================================
           UPDATE BULK SELECTION
        ========================================================= */

        function updateBulkSelection() {

            const selected =
                $('.product-checkbox:checked')
                .length;


            $('#selected-count')
                .text(selected);


            if (selected > 0) {

                $('#bulk-action-bar')
                    .removeClass('d-none');

            } else {

                $('#bulk-action-bar')
                    .addClass('d-none');

            }


            const total =
                $('.product-checkbox')
                .length;


            $('#select-all')
                .prop(
                    'checked',
                    total > 0 &&
                    selected === total
                );

        }


        /* =========================================================
           CLEAR SELECTION
        ========================================================= */

        $(document).on(
            'click',
            '#clear-selection-btn',
            function() {

                $('.product-checkbox')
                    .prop(
                        'checked',
                        false
                    );

                $('#select-all')
                    .prop(
                        'checked',
                        false
                    );

                updateBulkSelection();

            }
        );


        /* =========================================================
           BULK DELETE
        ========================================================= */

        $(document).on(
            'click',
            '#bulk-delete-btn',
            function() {

                const selectedIds =
                    $('.product-checkbox:checked')
                    .map(function() {

                        return $(this).val();

                    })
                    .get();


                if (selectedIds.length === 0) {

                    alert(
                        'Please select at least one product.'
                    );

                    return;

                }


                const confirmed =
                    confirm(
                        'Are you sure you want to move ' +
                        selectedIds.length +
                        ' selected product(s) to trash?'
                    );


                if (!confirmed) {
                    return;
                }


                $('#bulk-delete-inputs')
                    .empty();


                selectedIds.forEach(function(id) {

                    $('#bulk-delete-inputs')
                        .append(
                            $('<input>', {

                                type: 'hidden',

                                name: 'ids[]',

                                value: id

                            })
                        );

                });


                $('#bulk-delete-form')
                    .submit();

            }
        );


        /* =========================================================
           DELETE CONFIRMATION
        ========================================================= */

        $(document).on(
            'click',
            '.delete-product-btn',
            function(e) {

                if (
                    !confirm(
                        'Are you sure you want to move this product to trash?'
                    )
                ) {

                    e.preventDefault();

                }

            }
        );


        /* =========================================================
           CSV EXPORT
        ========================================================= */

        $('#export-csv-btn').on(
            'click',
            function() {

                const params =
                    new URLSearchParams();


                const keyword =
                    $('#search').val();

                const category =
                    $('#category-filter').val();

                const priceMin =
                    $('#price-min').val();

                const priceMax =
                    $('#price-max').val();

                const sort =
                    $('#sort').val();

                const stockStatus =
                    $('#stock-status').val();

                const status =
                    $('#status-filter').val();


                if (keyword) {

                    params.set(
                        'keyword',
                        keyword
                    );

                }


                if (category) {

                    params.set(
                        'category',
                        category
                    );

                }


                if (priceMin) {

                    params.set(
                        'price_min',
                        priceMin
                    );

                }


                if (priceMax) {

                    params.set(
                        'price_max',
                        priceMax
                    );

                }


                if (sort) {

                    params.set(
                        'sort',
                        sort
                    );

                }


                if (stockStatus) {

                    params.set(
                        'stock_status',
                        stockStatus
                    );

                }


                if (status) {

                    params.set(
                        'status',
                        status
                    );

                }


                window.location.href =
                    "{{ route('products.export') }}" +
                    "?" +
                    params.toString();

            }
        );


        /* =========================================================
           SEARCH SUGGESTIONS
        ========================================================= */

        $('#search').on(
            'keyup',
            function() {

                const keyword =
                    $(this).val();

                const suggestionsContainer =
                    $('#search-suggestions');


                if (keyword.length >= 2) {

                    $.ajax({

                        url: "{{ route('products.suggestions') }}",

                        type: "GET",

                        data: {

                            keyword: keyword

                        },


                        success: function(data) {

                            if (!data.length) {

                                suggestionsContainer
                                    .addClass('d-none')
                                    .empty();

                                return;

                            }


                            let html = '';


                            data.forEach(function(item) {

                                html += `

                                <div
                                    class="suggestion-item"
                                    data-keyword="${escapeHtml(item.label)}"
                                    style="cursor:pointer;"
                                >

                                    <div
                                        class="d-flex justify-content-between align-items-center gap-2">

                                        <span class="fw-semibold small">

                                            🔎
                                            ${escapeHtml(item.label)}

                                        </span>

                                        <span
                                            class="badge bg-light text-dark border suggestion-type">

                                            ${escapeHtml(item.type)}

                                        </span>

                                    </div>

                                </div>

                            `;

                            });


                            suggestionsContainer
                                .html(html)
                                .removeClass('d-none');

                        },


                        error: function() {

                            suggestionsContainer
                                .addClass('d-none')
                                .empty();

                        }

                    });

                } else {

                    suggestionsContainer
                        .addClass('d-none')
                        .empty();

                }


                clearTimeout(
                    window.productSearchTimer
                );


                window.productSearchTimer =
                    setTimeout(
                        function() {

                            fetch_data(1);

                        },
                        350
                    );

            }
        );


        /* =========================================================
           ESCAPE HTML
        ========================================================= */

        function escapeHtml(value) {

            return $('<div>')
                .text(value ?? '')
                .html();

        }


        /* =========================================================
           SUGGESTION CLICK
        ========================================================= */

        $(document).on(
            'click',
            '.suggestion-item',
            function() {

                $('#search').val(
                    $(this).data('keyword')
                );


                $('#search-suggestions')
                    .addClass('d-none')
                    .empty();


                fetch_data(1);

            }
        );


        /* =========================================================
           CLOSE SUGGESTIONS
        ========================================================= */

        $(document).on(
            'click',
            function(e) {

                if (
                    !$(e.target).closest(
                        '#search-suggestions, #search'
                    ).length
                ) {

                    $('#search-suggestions')
                        .addClass('d-none')
                        .empty();

                }

            }
        );


        /* =========================================================
           CATEGORY
        ========================================================= */

        $('#category-filter').on(
            'change',
            function() {

                fetch_data(1);

            }
        );


        /* =========================================================
           PRICE
        ========================================================= */

        $('#price-min, #price-max').on(
            'change',
            function() {

                fetch_data(1);

            }
        );


        /* =========================================================
           STOCK
        ========================================================= */

        $('#stock-status').on(
            'change',
            function() {

                fetch_data(1);

            }
        );


        /* =========================================================
           STATUS
        ========================================================= */

        $('#status-filter').on(
            'change',
            function() {

                fetch_data(1);

            }
        );


        /* =========================================================
           SORT
        ========================================================= */

        $('#sort').on(
            'change',
            function() {

                fetch_data(1);

            }
        );


        /* =========================================================
           APPLY FILTER
        ========================================================= */

        $('#filter-btn').on(
            'click',
            function() {

                fetch_data(1);

            }
        );


        /* =========================================================
           CLEAR FILTERS
        ========================================================= */

        function clearFilters() {

            $('#search').val('');

            $('#category-filter').val('');

            $('#price-min').val('');

            $('#price-max').val('');

            $('#sort').val('');

            $('#stock-status').val('');

            $('#status-filter').val('');


            $('#search-suggestions')
                .addClass('d-none')
                .empty();


            fetch_data(1);

        }


        $('#clear-filter-btn').on(
            'click',
            function() {

                clearFilters();

            }
        );


        $(document).on(
            'click',
            '#empty-clear-btn',
            function() {

                clearFilters();

            }
        );


        /* =========================================================
           CLEAR SEARCH HISTORY
        ========================================================= */

        $(document).on(
            'click',
            '.clear-history-btn',
            function() {

                $.get(
                    "{{ route('products.index') }}", {
                        clear_history: 1
                    }
                );


                $('#search-history-wrapper')
                    .fadeOut(
                        200,
                        function() {

                            $(this).remove();

                        }
                    );

            }
        );


        /* =========================================================
           SEARCH HISTORY CLICK
        ========================================================= */

        $(document).on(
            'click',
            '.search-history-chip',
            function() {

                $('#search').val(
                    $(this).data('keyword')
                );


                fetch_data(1);

            }
        );


        /* =========================================================
           INITIAL BINDINGS
        ========================================================= */

        bindPagination();

        bindStatusToggle();

        updateBulkSelection();


    });
</script>

@endpush