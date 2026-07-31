@extends('layouts.admin')

@section('content')
<div class="py-4">
    <div class="mb-4">
        <h2 class="fw-bold">📊 Dashboard Overview</h2>
        <p class="text-muted">Welcome back! Here's what's happening with your store.</p>
    </div>

    {{-- STATISTICS CARDS --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-bold">Total Products</p>
                            <h3 class="fw-bold mb-0">{{ $totalProducts }}</h3>
                        </div>
                        <div class="fs-1 text-primary opacity-25">📦</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-bold">Active Products</p>
                            <h3 class="fw-bold mb-0">{{ $activeProducts }}</h3>
                        </div>
                        <div class="fs-1 text-success opacity-25">✅</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-bold">Out of Stock</p>
                            <h3 class="fw-bold mb-0">{{ $outOfStockProducts }}</h3>
                        </div>
                        <div class="fs-1 text-danger opacity-25">⚠️</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-bold">Low Stock</p>
                            <h3 class="fw-bold mb-0">{{ $lowStockProducts }}</h3>
                        </div>
                        <div class="fs-1 text-warning opacity-25">📉</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- LOW STOCK ALERT --}}
    @if($lowStockProducts > 0)
        <div class="alert alert-warning shadow-sm d-flex align-items-center mb-4" role="alert">
            <span class="fs-4 me-2">⚠️</span>
            <div>
                <strong>Low Stock Alert!</strong> {{ $lowStockProducts }} product(s) have low stock. 
                <a href="{{ route('products.index') }}" class="alert-link">View Products</a>
            </div>
        </div>
    @endif

    {{-- CHARTS ROW --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">📊 Category-wise Products</h5>
                </div>
                <div class="card-body">
                    @if($categoryStats->count() > 0)
                        <canvas id="categoryChart" height="200"></canvas>
                    @else
                        <p class="text-muted text-center py-4">No category data available yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">📈 Products Added (Last 6 Months)</h5>
                </div>
                <div class="card-body">
                    @if($productsByMonth->count() > 0)
                        <canvas id="productsChart" height="200"></canvas>
                    @else
                        <p class="text-muted text-center py-4">No products data available yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- RECENT PRODUCTS --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">📦 Recent Products</h5>
            <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price (₹)</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Added On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentProducts as $product)
                            <tr>
                                <td class="fw-semibold">{{ $product->name }}</td>
                                <td>{{ $product->category }}</td>
                                <td class="fw-bold">₹{{ number_format($product->price, 2) }}</td>
                                <td>
                                    @if($product->stock <= 0)
                                        <span class="badge bg-danger">Out of Stock</span>
                                    @elseif($product->stock <= ($product->low_stock_threshold ?? 5))
                                        <span class="badge bg-warning text-dark">Low ({{ $product->stock }})</span>
                                    @else
                                        <span class="badge bg-success">{{ $product->stock }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $product->status == 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </td>
                                <td>{{ $product->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No products yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const categoryData = @json($categoryStats);
    const productsData = @json($productsByMonth);

    @if($categoryStats->count() > 0)
        new Chart(document.getElementById('categoryChart'), {
            type: 'doughnut',
            data: {
                labels: categoryData.map(item => item.category || 'Uncategorized'),
                datasets: [{
                    data: categoryData.map(item => item.count),
                    backgroundColor: [
                        '#4F46E5', '#7C3AED', '#EC4899', '#F59E0B',
                        '#10B981', '#3B82F6', '#EF4444', '#8B5CF6'
                    ],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 15, usePointStyle: true } }
                }
            }
        });
    @endif

    @if($productsByMonth->count() > 0)
        const productLabels = productsData.map(item => `${item.month}/${item.year}`);
        const productValues = productsData.map(item => item.total);

        new Chart(document.getElementById('productsChart'), {
            type: 'line',
            data: {
                labels: productLabels,
                datasets: [{
                    label: 'Products Added',
                    data: productValues,
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#10B981',
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
                            callback: function(value) { return Math.round(value); }
                        }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    @endif
</script>
@endpush
