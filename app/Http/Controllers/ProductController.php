<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->filled('clear_history')) {
            session()->forget('search_history');
        }

        $query = Product::where('status', '!=', 'deleted');

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            if (is_numeric($keyword)) {
                $query->where('price', (float)$keyword);
            } else {
                $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                      ->orWhere('category', 'like', "%{$keyword}%")
                      ->orWhere('color', 'like', "%{$keyword}%")
                      ->orWhere('size', 'like', "%{$keyword}%")
                      ->orWhere('details', 'like', "%{$keyword}%");
                });
            }

            $searchHistory = session()->get('search_history', []);
            $searchHistory = array_filter($searchHistory, fn($item) => $item !== $keyword);
            array_unshift($searchHistory, $keyword);
            $searchHistory = array_slice($searchHistory, 0, 10);
            session()->put('search_history', $searchHistory);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('price_min') && is_numeric($request->price_min)) {
            $query->where('price', '>=', (float)$request->price_min);
        }

        if ($request->filled('price_max') && is_numeric($request->price_max)) {
            $query->where('price', '<=', (float)$request->price_max);
        }

        if ($request->filled('sort') && in_array($request->sort, ['price-asc', 'price-desc'])) {
            $query->orderBy('price', $request->sort == 'price-asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(10)->appends($request->query());
        $categories = Product::select('category')->distinct()->orderBy('category')->pluck('category');
        $searchHistory = session()->get('search_history', []);

        return view('products.index', compact('products', 'categories', 'searchHistory'));
    }

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
                ->select('name', 'category', 'color', 'size', 'price')
                ->limit(8)
                ->get();

            $seen = [];
            foreach ($products as $product) {
                $label = $product->name;
                if (!in_array($label, $seen)) {
                    $suggestions[] = ['label' => $label, 'type' => 'Product'];
                    $seen[] = $label;
                }
                if (!in_array($product->category, $seen)) {
                    $suggestions[] = ['label' => $product->category, 'type' => 'Category'];
                    $seen[] = $product->category;
                }
                if (!in_array($product->color, $seen)) {
                    $suggestions[] = ['label' => $product->color, 'type' => 'Color'];
                    $seen[] = $product->color;
                }
            }
        }

        return response()->json($suggestions);
    }

    public function create()
    {
        $categories = Product::select('category')->distinct()->orderBy('category')->pluck('category');
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'details'   => 'required',
            'size'      => 'required',
            'color'     => 'required',
            'category'  => 'required',
            'price'     => 'required|numeric',
            'stock'     => 'required|integer|min:0',
            'image'     => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $imagePath = 'images/' . $imageName;
        }

        Product::create([
            'name'      => $request->name,
            'details'   => $request->details,
            'size'      => $request->size,
            'color'     => $request->color,
            'category'  => $request->category,
            'price'     => $request->price,
            'stock'     => $request->stock ?? 0,
            'low_stock_threshold' => $request->low_stock_threshold ?? 5,
            'image'     => $imagePath,
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Product::select('category')->distinct()->orderBy('category')->pluck('category');
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'      => 'required',
            'details'   => 'required',
            'size'      => 'required',
            'color'     => 'required',
            'category'  => 'required',
            'price'     => 'required|numeric',
            'stock'     => 'required|integer|min:0',
            'image'     => 'nullable|image|max:2048',
        ]);

        $imagePath = $product->image;

        if ($request->hasFile('image')) {
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }
            $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $imagePath = 'images/' . $imageName;
        }

        $product->update([
            'name'      => $request->name,
            'details'   => $request->details,
            'size'      => $request->size,
            'color'     => $request->color,
            'category'  => $request->category,
            'price'     => $request->price,
            'stock'     => $request->stock,
            'low_stock_threshold' => $request->low_stock_threshold ?? 5,
            'image'     => $imagePath,
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
