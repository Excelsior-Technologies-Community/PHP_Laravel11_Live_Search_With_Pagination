<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Clear Search History
        |--------------------------------------------------------------------------
        */
        if ($request->filled('clear_history')) {
            session()->forget('search_history');
        }

        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        */
        $query = Product::where('status', '!=', 'deleted');

        /*
        |--------------------------------------------------------------------------
        | Live Search
        |--------------------------------------------------------------------------
        */
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            if (is_numeric($keyword)) {

                // Number → exact price search
                $query->where('price', (float) $keyword);

            } else {

                // Text → search multiple fields
                $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                        ->orWhere('category', 'like', "%{$keyword}%")
                        ->orWhere('color', 'like', "%{$keyword}%")
                        ->orWhere('size', 'like', "%{$keyword}%")
                        ->orWhere('details', 'like', "%{$keyword}%");
                });
            }

            /*
            |--------------------------------------------------------------------------
            | Search History
            |--------------------------------------------------------------------------
            */
            $searchHistory = session()->get('search_history', []);

            $searchHistory = array_filter(
                $searchHistory,
                fn ($item) => $item !== $keyword
            );

            array_unshift($searchHistory, $keyword);

            $searchHistory = array_slice($searchHistory, 0, 10);

            session()->put('search_history', $searchHistory);
        }

        /*
        |--------------------------------------------------------------------------
        | Category Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        /*
        |--------------------------------------------------------------------------
        | Price Minimum
        |--------------------------------------------------------------------------
        */
        if ($request->filled('price_min') && is_numeric($request->price_min)) {
            $query->where('price', '>=', (float) $request->price_min);
        }

        /*
        |--------------------------------------------------------------------------
        | Price Maximum
        |--------------------------------------------------------------------------
        */
        if ($request->filled('price_max') && is_numeric($request->price_max)) {
            $query->where('price', '<=', (float) $request->price_max);
        }

        /*
        |--------------------------------------------------------------------------
        | NEW: Stock Availability Filter
        |--------------------------------------------------------------------------
        |
        | all       → all products
        | in-stock  → stock greater than 0 and above low-stock threshold
        | low-stock → stock greater than 0 but below/equal threshold
        | out-stock → stock equal to 0
        |
        */
        if ($request->filled('stock_status')) {

            switch ($request->stock_status) {

                case 'in-stock':
                    $query->where('stock', '>', 0)
                        ->whereColumn('stock', '>', 'low_stock_threshold');
                    break;

                case 'low-stock':
                    $query->where('stock', '>', 0)
                        ->whereColumn('stock', '<=', 'low_stock_threshold');
                    break;

                case 'out-stock':
                    $query->where('stock', '<=', 0);
                    break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */
        if (
            $request->filled('sort') &&
            in_array($request->sort, ['price-asc', 'price-desc'])
        ) {

            $query->orderBy(
                'price',
                $request->sort === 'price-asc' ? 'asc' : 'desc'
            );

        } else {

            $query->latest();
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */
        $products = $query
            ->paginate(10)
            ->appends($request->query());

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */
        $categories = Product::where('status', '!=', 'deleted')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        /*
        |--------------------------------------------------------------------------
        | Search History
        |--------------------------------------------------------------------------
        */
        $searchHistory = session()->get('search_history', []);

        /*
        |--------------------------------------------------------------------------
        | Result Count
        |--------------------------------------------------------------------------
        */
        $resultCount = $products->total();

        return view('products.index', compact(
            'products',
            'categories',
            'searchHistory',
            'resultCount'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Search Suggestions
    |--------------------------------------------------------------------------
    */
    public function suggestions(Request $request)
    {
        $keyword = $request->get('keyword', '');

        $suggestions = [];

        if (strlen($keyword) >= 2) {

            $products = Product::where('status', '!=', 'deleted')
                ->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                        ->orWhere('category', 'like', "%{$keyword}%")
                        ->orWhere('color', 'like', "%{$keyword}%")
                        ->orWhere('size', 'like', "%{$keyword}%");
                })
                ->select(
                    'name',
                    'category',
                    'color',
                    'size',
                    'price'
                )
                ->limit(8)
                ->get();

            $seen = [];

            foreach ($products as $product) {

                /*
                |--------------------------------------------------------------------------
                | Product Suggestion
                |--------------------------------------------------------------------------
                */
                if (
                    !empty($product->name) &&
                    !in_array($product->name, $seen)
                ) {

                    $suggestions[] = [
                        'label' => $product->name,
                        'type' => 'Product',
                    ];

                    $seen[] = $product->name;
                }

                /*
                |--------------------------------------------------------------------------
                | Category Suggestion
                |--------------------------------------------------------------------------
                */
                if (
                    !empty($product->category) &&
                    !in_array($product->category, $seen)
                ) {

                    $suggestions[] = [
                        'label' => $product->category,
                        'type' => 'Category',
                    ];

                    $seen[] = $product->category;
                }

                /*
                |--------------------------------------------------------------------------
                | Color Suggestion
                |--------------------------------------------------------------------------
                */
                if (
                    !empty($product->color) &&
                    !in_array($product->color, $seen)
                ) {

                    $suggestions[] = [
                        'label' => $product->color,
                        'type' => 'Color',
                    ];

                    $seen[] = $product->color;
                }
            }
        }

        return response()->json($suggestions);
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $categories = Product::where('status', '!=', 'deleted')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('products.create', compact('categories'));
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'details' => 'required',
            'size' => 'required',
            'color' => 'required',
            'category' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:1',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {

            $imageName = time()
                . '_'
                . uniqid()
                . '.'
                . $request->image->extension();

            $request->image->move(
                public_path('images'),
                $imageName
            );

            $imagePath = 'images/' . $imageName;
        }

        Product::create([
            'name' => $request->name,
            'details' => $request->details,
            'size' => $request->size,
            'color' => $request->color,
            'category' => $request->category,
            'price' => $request->price,
            'stock' => $request->stock,
            'low_stock_threshold' => $request->low_stock_threshold,
            'image' => $imagePath,
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */
    public function edit(Product $product)
    {
        $categories = Product::where('status', '!=', 'deleted')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view(
            'products.edit',
            compact('product', 'categories')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'details' => 'required',
            'size' => 'required',
            'color' => 'required',
            'category' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:1',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = $product->image;

        if ($request->hasFile('image')) {

            if (
                $product->image &&
                file_exists(public_path($product->image))
            ) {
                unlink(public_path($product->image));
            }

            $imageName = time()
                . '_'
                . uniqid()
                . '.'
                . $request->image->extension();

            $request->image->move(
                public_path('images'),
                $imageName
            );

            $imagePath = 'images/' . $imageName;
        }

        $product->update([
            'name' => $request->name,
            'details' => $request->details,
            'size' => $request->size,
            'color' => $request->color,
            'category' => $request->category,
            'price' => $request->price,
            'stock' => $request->stock,
            'low_stock_threshold' => $request->low_stock_threshold,
            'image' => $imagePath,
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}