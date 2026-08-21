<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Clear Search History
        |--------------------------------------------------------------------------
        */

        if ($request->boolean('clear_history')) {
            session()->forget('search_history');
        }


        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        |
        | Soft deleted products are automatically excluded when the Product
        | model uses SoftDeletes.
        |
        */

        $query = Product::query();


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('keyword')) {

            $keyword = trim($request->keyword);

            if ($keyword !== '') {

                /*
                |--------------------------------------------------------------------------
                | Numeric Search
                |--------------------------------------------------------------------------
                |
                | Search both:
                | - Product ID
                | - Exact price
                |
                */

                if (is_numeric($keyword)) {

                    $query->where(function ($q) use ($keyword) {

                        $q->where(
                            'id',
                            (int) $keyword
                        )
                            ->orWhere(
                                'price',
                                (float) $keyword
                            );
                    });
                } else {

                    $query->where(function ($q) use ($keyword) {

                        $q->where(
                            'name',
                            'like',
                            "%{$keyword}%"
                        )
                            ->orWhere(
                                'category',
                                'like',
                                "%{$keyword}%"
                            )
                            ->orWhere(
                                'color',
                                'like',
                                "%{$keyword}%"
                            )
                            ->orWhere(
                                'size',
                                'like',
                                "%{$keyword}%"
                            )
                            ->orWhere(
                                'details',
                                'like',
                                "%{$keyword}%"
                            );
                    });
                }


                /*
                |--------------------------------------------------------------------------
                | Search History
                |--------------------------------------------------------------------------
                */

                $searchHistory = session()->get(
                    'search_history',
                    []
                );

                $searchHistory = array_values(
                    array_filter(
                        $searchHistory,
                        fn($item) => $item !== $keyword
                    )
                );

                array_unshift(
                    $searchHistory,
                    $keyword
                );

                $searchHistory = array_slice(
                    $searchHistory,
                    0,
                    10
                );

                session()->put(
                    'search_history',
                    $searchHistory
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Category Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('category')) {

            $query->where(
                'category',
                $request->category
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Minimum Price
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('price_min') &&
            is_numeric($request->price_min)
        ) {

            $query->where(
                'price',
                '>=',
                (float) $request->price_min
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Maximum Price
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('price_max') &&
            is_numeric($request->price_max)
        ) {

            $query->where(
                'price',
                '<=',
                (float) $request->price_max
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Stock Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('stock_status')) {

            switch ($request->stock_status) {

                /*
                |--------------------------------------------------------------------------
                | In Stock
                |--------------------------------------------------------------------------
                */

                case 'in-stock':

                    $query
                        ->where('stock', '>', 0)
                        ->whereColumn(
                            'stock',
                            '>',
                            'low_stock_threshold'
                        );

                    break;


                /*
                |--------------------------------------------------------------------------
                | Low Stock
                |--------------------------------------------------------------------------
                */

                case 'low-stock':

                    $query
                        ->where('stock', '>', 0)
                        ->whereColumn(
                            'stock',
                            '<=',
                            'low_stock_threshold'
                        );

                    break;


                /*
                |--------------------------------------------------------------------------
                | Out Of Stock
                |--------------------------------------------------------------------------
                */

                case 'out-stock':

                    $query->where(
                        'stock',
                        '<=',
                        0
                    );

                    break;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('status') &&
            in_array(
                $request->status,
                ['active', 'inactive'],
                true
            )
        ) {

            $query->where(
                'status',
                $request->status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        |
        | DEFAULT = ID ASC
        |
        | This means:
        |
        | 1
        | 2
        | 3
        | 4
        | 5
        |
        */

        switch ($request->get('sort')) {

            /*
            |--------------------------------------------------------------------------
            | ID ASC
            |--------------------------------------------------------------------------
            */

            case 'id-asc':

                $query
                    ->orderBy('id', 'asc');

                break;


            /*
            |--------------------------------------------------------------------------
            | ID DESC
            |--------------------------------------------------------------------------
            */

            case 'id-desc':

                $query
                    ->orderBy('id', 'desc');

                break;


            /*
            |--------------------------------------------------------------------------
            | Price ASC
            |--------------------------------------------------------------------------
            */

            case 'price-asc':

                $query
                    ->orderBy('price', 'asc')
                    ->orderBy('id', 'asc');

                break;


            /*
            |--------------------------------------------------------------------------
            | Price DESC
            |--------------------------------------------------------------------------
            */

            case 'price-desc':

                $query
                    ->orderBy('price', 'desc')
                    ->orderBy('id', 'asc');

                break;


            /*
            |--------------------------------------------------------------------------
            | Name ASC
            |--------------------------------------------------------------------------
            */

            case 'name-asc':

                $query
                    ->orderBy('name', 'asc')
                    ->orderBy('id', 'asc');

                break;


            /*
            |--------------------------------------------------------------------------
            | Name DESC
            |--------------------------------------------------------------------------
            */

            case 'name-desc':

                $query
                    ->orderBy('name', 'desc')
                    ->orderBy('id', 'asc');

                break;


            /*
            |--------------------------------------------------------------------------
            | Oldest
            |--------------------------------------------------------------------------
            */

            case 'oldest':

                $query
                    ->orderBy('created_at', 'asc')
                    ->orderBy('id', 'asc');

                break;


            /*
            |--------------------------------------------------------------------------
            | Latest
            |--------------------------------------------------------------------------
            */

            case 'latest':

                $query
                    ->orderBy('created_at', 'desc')
                    ->orderBy('id', 'desc');

                break;


            /*
            |--------------------------------------------------------------------------
            | Default
            |--------------------------------------------------------------------------
            */

            default:

                $query
                    ->orderBy('id', 'asc');

                break;
        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $products = $query
            ->paginate(5)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        $categories = Product::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->select('category')
            ->distinct()
            ->orderBy('category', 'asc')
            ->pluck('category');


        /*
        |--------------------------------------------------------------------------
        | Search History
        |--------------------------------------------------------------------------
        */

        $searchHistory = session()->get(
            'search_history',
            []
        );


        /*
        |--------------------------------------------------------------------------
        | Result Count
        |--------------------------------------------------------------------------
        */

        $resultCount = $products->total();


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'products.index',
            compact(
                'products',
                'categories',
                'searchHistory',
                'resultCount'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SEARCH SUGGESTIONS
    |--------------------------------------------------------------------------
    */

    public function suggestions(Request $request)
    {
        $keyword = trim(
            $request->get('keyword', '')
        );

        $suggestions = [];


        if (mb_strlen($keyword) < 2) {
            return response()->json([]);
        }


        /*
        |--------------------------------------------------------------------------
        | Find Products
        |--------------------------------------------------------------------------
        */

        $products = Product::query()
            ->where(function ($q) use ($keyword) {

                $q->where(
                    'name',
                    'like',
                    "%{$keyword}%"
                )
                    ->orWhere(
                        'category',
                        'like',
                        "%{$keyword}%"
                    )
                    ->orWhere(
                        'color',
                        'like',
                        "%{$keyword}%"
                    )
                    ->orWhere(
                        'size',
                        'like',
                        "%{$keyword}%"
                    );
            })
            ->select(
                'name',
                'category',
                'color',
                'size'
            )
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Remove Duplicates
        |--------------------------------------------------------------------------
        */

        $seen = [];


        foreach ($products as $product) {

            /*
            |--------------------------------------------------------------------------
            | Product Name
            |--------------------------------------------------------------------------
            */

            if (
                !empty($product->name) &&
                !in_array(
                    $product->name,
                    $seen,
                    true
                )
            ) {

                $suggestions[] = [
                    'label' => $product->name,
                    'type' => 'Product',
                ];

                $seen[] = $product->name;
            }


            /*
            |--------------------------------------------------------------------------
            | Category
            |--------------------------------------------------------------------------
            */

            if (
                !empty($product->category) &&
                !in_array(
                    $product->category,
                    $seen,
                    true
                )
            ) {

                $suggestions[] = [
                    'label' => $product->category,
                    'type' => 'Category',
                ];

                $seen[] = $product->category;
            }


            /*
            |--------------------------------------------------------------------------
            | Color
            |--------------------------------------------------------------------------
            */

            if (
                !empty($product->color) &&
                !in_array(
                    $product->color,
                    $seen,
                    true
                )
            ) {

                $suggestions[] = [
                    'label' => $product->color,
                    'type' => 'Color',
                ];

                $seen[] = $product->color;
            }


            /*
            |--------------------------------------------------------------------------
            | Size
            |--------------------------------------------------------------------------
            */

            if (
                !empty($product->size) &&
                !in_array(
                    $product->size,
                    $seen,
                    true
                )
            ) {

                $suggestions[] = [
                    'label' => $product->size,
                    'type' => 'Size',
                ];

                $seen[] = $product->size;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Limit Suggestions
        |--------------------------------------------------------------------------
        */

        return response()->json(
            array_slice(
                $suggestions,
                0,
                8
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $categories = Product::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');


        return view(
            'products.create',
            compact('categories')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'details' => [
                'required',
                'string',
            ],

            'size' => [
                'required',
                'string',
                'max:100',
            ],

            'color' => [
                'required',
                'string',
                'max:100',
            ],

            'category' => [
                'required',
                'string',
                'max:100',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'low_stock_threshold' => [
                'required',
                'integer',
                'min:1',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Upload Image
        |--------------------------------------------------------------------------
        */

        $imagePath = null;


        if ($request->hasFile('image')) {

            $imageName =
                time() .
                '_' .
                uniqid() .
                '.' .
                $request->file('image')->extension();


            $request->file('image')->move(
                public_path('images'),
                $imageName
            );


            $imagePath =
                'images/' .
                $imageName;
        }


        /*
        |--------------------------------------------------------------------------
        | Create Product
        |--------------------------------------------------------------------------
        */

        Product::create([
            'name' => $validated['name'],
            'details' => $validated['details'],
            'size' => $validated['size'],
            'color' => $validated['color'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'low_stock_threshold' =>
            $validated['low_stock_threshold'],
            'image' => $imagePath,
            'status' => 'active',
        ]);


        return redirect()
            ->route('products.index')
            ->with(
                'success',
                'Product created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(Product $product)
    {
        return view(
            'products.show',
            compact('product')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Product $product)
    {
        $categories = Product::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');


        return view(
            'products.edit',
            compact(
                'product',
                'categories'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Product $product
    ) {

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'details' => [
                'required',
                'string',
            ],

            'size' => [
                'required',
                'string',
                'max:100',
            ],

            'color' => [
                'required',
                'string',
                'max:100',
            ],

            'category' => [
                'required',
                'string',
                'max:100',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'low_stock_threshold' => [
                'required',
                'integer',
                'min:1',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);


        $imagePath = $product->image;


        /*
        |--------------------------------------------------------------------------
        | Replace Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            if (
                $product->image &&
                file_exists(
                    public_path($product->image)
                )
            ) {

                unlink(
                    public_path($product->image)
                );
            }


            $imageName =
                time() .
                '_' .
                uniqid() .
                '.' .
                $request->file('image')->extension();


            $request->file('image')->move(
                public_path('images'),
                $imageName
            );


            $imagePath =
                'images/' .
                $imageName;
        }


        /*
        |--------------------------------------------------------------------------
        | Update Product
        |--------------------------------------------------------------------------
        */

        $product->update([
            'name' => $validated['name'],
            'details' => $validated['details'],
            'size' => $validated['size'],
            'color' => $validated['color'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'low_stock_threshold' =>
            $validated['low_stock_threshold'],
            'image' => $imagePath,
        ]);


        return redirect()
            ->route('products.index')
            ->with(
                'success',
                'Product updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | TOGGLE STATUS
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(Product $product)
    {
        $product->status =
            $product->status === 'active'
            ? 'inactive'
            : 'active';


        $product->save();


        return response()->json([
            'success' => true,
            'status' => $product->status,
            'message' =>
            'Product status updated successfully.',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | CSV EXPORT
    |--------------------------------------------------------------------------
    */

    public function export(Request $request)
    {
        $query = Product::query();


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('keyword')) {

            $keyword = trim(
                $request->keyword
            );


            if ($keyword !== '') {

                if (is_numeric($keyword)) {

                    $query->where(function ($q) use ($keyword) {

                        $q->where(
                            'id',
                            (int) $keyword
                        )
                            ->orWhere(
                                'price',
                                (float) $keyword
                            );
                    });
                } else {

                    $query->where(function ($q) use ($keyword) {

                        $q->where(
                            'name',
                            'like',
                            "%{$keyword}%"
                        )
                            ->orWhere(
                                'category',
                                'like',
                                "%{$keyword}%"
                            )
                            ->orWhere(
                                'color',
                                'like',
                                "%{$keyword}%"
                            )
                            ->orWhere(
                                'size',
                                'like',
                                "%{$keyword}%"
                            )
                            ->orWhere(
                                'details',
                                'like',
                                "%{$keyword}%"
                            );
                    });
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Category
        |--------------------------------------------------------------------------
        */

        if ($request->filled('category')) {

            $query->where(
                'category',
                $request->category
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Price Minimum
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('price_min') &&
            is_numeric($request->price_min)
        ) {

            $query->where(
                'price',
                '>=',
                (float) $request->price_min
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Price Maximum
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('price_max') &&
            is_numeric($request->price_max)
        ) {

            $query->where(
                'price',
                '<=',
                (float) $request->price_max
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Stock
        |--------------------------------------------------------------------------
        */

        if ($request->filled('stock_status')) {

            switch ($request->stock_status) {

                case 'in-stock':

                    $query
                        ->where('stock', '>', 0)
                        ->whereColumn(
                            'stock',
                            '>',
                            'low_stock_threshold'
                        );

                    break;


                case 'low-stock':

                    $query
                        ->where('stock', '>', 0)
                        ->whereColumn(
                            'stock',
                            '<=',
                            'low_stock_threshold'
                        );

                    break;


                case 'out-stock':

                    $query->where(
                        'stock',
                        '<=',
                        0
                    );

                    break;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('status') &&
            in_array(
                $request->status,
                ['active', 'inactive'],
                true
            )
        ) {

            $query->where(
                'status',
                $request->status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        switch ($request->get('sort')) {

            case 'id-asc':

                $query->orderBy(
                    'id',
                    'asc'
                );

                break;


            case 'id-desc':

                $query->orderBy(
                    'id',
                    'desc'
                );

                break;


            case 'price-asc':

                $query
                    ->orderBy('price', 'asc')
                    ->orderBy('id', 'asc');

                break;


            case 'price-desc':

                $query
                    ->orderBy('price', 'desc')
                    ->orderBy('id', 'asc');

                break;


            case 'name-asc':

                $query
                    ->orderBy('name', 'asc')
                    ->orderBy('id', 'asc');

                break;


            case 'name-desc':

                $query
                    ->orderBy('name', 'desc')
                    ->orderBy('id', 'asc');

                break;


            case 'oldest':

                $query
                    ->orderBy('created_at', 'asc')
                    ->orderBy('id', 'asc');

                break;


            case 'latest':

                $query
                    ->orderBy('created_at', 'desc')
                    ->orderBy('id', 'desc');

                break;


            default:

                $query->orderBy(
                    'id',
                    'asc'
                );

                break;
        }


        $products = $query->get();


        /*
        |--------------------------------------------------------------------------
        | Filename
        |--------------------------------------------------------------------------
        */

        $filename =
            'products_' .
            now()->format('Y-m-d_H-i-s') .
            '.csv';


        /*
        |--------------------------------------------------------------------------
        | CSV Download
        |--------------------------------------------------------------------------
        */

        return response()->streamDownload(

            function () use ($products) {

                $handle = fopen(
                    'php://output',
                    'w'
                );


                /*
                |--------------------------------------------------------------------------
                | UTF-8 BOM
                |--------------------------------------------------------------------------
                */

                fwrite(
                    $handle,
                    "\xEF\xBB\xBF"
                );


                fputcsv($handle, [
                    'ID',
                    'Name',
                    'Details',
                    'Size',
                    'Color',
                    'Category',
                    'Price',
                    'Stock',
                    'Low Stock Threshold',
                    'Status',
                    'Created At',
                ]);


                foreach ($products as $product) {

                    fputcsv($handle, [
                        $product->id,
                        $product->name,
                        $product->details,
                        $product->size,
                        $product->color,
                        $product->category,
                        $product->price,
                        $product->stock,
                        $product->low_stock_threshold,
                        $product->status,
                        optional(
                            $product->created_at
                        )->format(
                            'Y-m-d H:i:s'
                        ),
                    ]);
                }


                fclose($handle);
            },

            $filename,

            [
                'Content-Type' =>
                'text/csv; charset=UTF-8',
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | BULK DELETE
    |--------------------------------------------------------------------------
    */

    public function bulkDelete(
        Request $request
    ) {

        $validated = $request->validate([
            'ids' => [
                'required',
                'array',
                'min:1',
            ],

            'ids.*' => [
                'integer',
                'exists:products,id',
            ],
        ]);


        $products = Product::query()
            ->whereIn(
                'id',
                $validated['ids']
            )
            ->get();


        foreach ($products as $product) {

            $product->delete();
        }


        /*
        |--------------------------------------------------------------------------
        | Redirect Instead Of JSON
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('products.index')
            ->with(
                'success',
                $products->count() .
                    ' product(s) moved to trash.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DUPLICATE
    |--------------------------------------------------------------------------
    */

    public function duplicate(
        Product $product
    ) {

        $copy = $product->replicate();


        $copy->name =
            $product->name .
            ' (Copy)';


        $copy->status = 'active';


        /*
        |--------------------------------------------------------------------------
        | Duplicate Image
        |--------------------------------------------------------------------------
        */

        if (
            $product->image &&
            file_exists(
                public_path($product->image)
            )
        ) {

            $extension = pathinfo(
                $product->image,
                PATHINFO_EXTENSION
            );


            $newImageName =
                time() .
                '_' .
                uniqid() .
                '.' .
                $extension;


            $imagesDirectory =
                public_path('images');


            if (
                !is_dir($imagesDirectory)
            ) {

                mkdir(
                    $imagesDirectory,
                    0755,
                    true
                );
            }


            copy(
                public_path($product->image),
                $imagesDirectory .
                    DIRECTORY_SEPARATOR .
                    $newImageName
            );


            $copy->image =
                'images/' .
                $newImageName;
        }


        $copy->save();


        return redirect()
            ->route('products.index')
            ->with(
                'success',
                'Product duplicated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE → TRASH
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Product $product
    ) {

        $product->delete();


        return redirect()
            ->route('products.index')
            ->with(
                'success',
                'Product moved to trash.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | TRASH
    |--------------------------------------------------------------------------
    */

    public function trash()
    {
        $products = Product::onlyTrashed()
            ->orderBy(
                'deleted_at',
                'desc'
            )
            ->orderBy(
                'id',
                'desc'
            )
            ->paginate(5)
            ->withQueryString();


        return view(
            'products.trash',
            compact('products')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RESTORE
    |--------------------------------------------------------------------------
    */

    public function restore($id)
    {
        $product = Product::onlyTrashed()
            ->findOrFail($id);


        $product->restore();


        return redirect()
            ->route('products.trash')
            ->with(
                'success',
                'Product restored successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | FORCE DELETE
    |--------------------------------------------------------------------------
    */

    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()
            ->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Delete Product Image
        |--------------------------------------------------------------------------
        */

        if (
            $product->image &&
            file_exists(
                public_path($product->image)
            )
        ) {

            unlink(
                public_path($product->image)
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Permanent Delete
        |--------------------------------------------------------------------------
        */

        $product->forceDelete();


        return redirect()
            ->route('products.trash')
            ->with(
                'success',
                'Product permanently deleted.'
            );
    }
}
