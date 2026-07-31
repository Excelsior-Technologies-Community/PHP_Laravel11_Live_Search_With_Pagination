<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Area</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f6f7;
        }
        .product-img {
            height: 220px;
            width: 100%;
            object-fit: cover;
            border-bottom: 1px solid #ddd;
        }
        .navbar-customer {
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        }
        .navbar-customer .navbar-brand {
            font-weight: 700;
            color: #4F46E5 !important;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light navbar-customer mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('customer.products') }}">🛍️ Customer Store</a>
            <div class="ms-auto">
                <a href="{{ route('customer.products') }}" class="btn btn-outline-primary btn-sm">Browse Products</a>
            </div>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
