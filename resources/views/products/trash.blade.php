@extends('layouts.admin')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                ♻️ Trash
            </h2>

            <p class="text-muted mb-0">
                Restore or permanently delete products.
            </p>

        </div>

        <a
            href="{{ route('products.index') }}"
            class="btn btn-outline-primary"
        >
            ← Products
        </a>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    <div class="card shadow-sm border-0">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark">

                        <tr>

                            <th>ID</th>

                            <th>Name</th>

                            <th>Category</th>

                            <th>Price</th>

                            <th>Deleted At</th>

                            <th class="text-center">
                                Actions
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($products as $product)

                            <tr>

                                <td>
                                    {{ $product->id }}
                                </td>

                                <td class="fw-bold">
                                    {{ $product->name }}
                                </td>

                                <td>
                                    {{ $product->category }}
                                </td>

                                <td>
                                    ₹{{ number_format($product->price, 2) }}
                                </td>

                                <td>
                                    {{ $product->deleted_at->format('d M Y h:i A') }}
                                </td>

                                <td class="text-center">

                                    <form
                                        action="{{ route('products.restore', $product->id) }}"
                                        method="POST"
                                        class="d-inline"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            class="btn btn-success btn-sm"
                                        >
                                            ♻️ Restore
                                        </button>

                                    </form>


                                    <form
                                        action="{{ route('products.forceDelete', $product->id) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Permanently delete this product? This cannot be undone.')"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="btn btn-danger btn-sm"
                                        >
                                            🗑 Delete Forever
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center py-5 text-muted"
                                >

                                    <div class="fs-1">
                                        🗑️
                                    </div>

                                    <h5>
                                        Trash is empty
                                    </h5>

                                    <p class="mb-0">
                                        Deleted products will appear here.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            <div class="p-3">

                {{ $products->links() }}

            </div>

        </div>

    </div>

</div>

@endsection