<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CustomerProductsController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('status', 'active');

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

        $products = $query->paginate(12)->appends($request->query());
        $categories = Product::select('category')->distinct()->orderBy('category')->pluck('category');

        return view('customer.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        if ($product->status !== 'active') {
            abort(404);
        }

        $relatedProducts = Product::where('status', 'active')
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('customer.show', compact('product', 'relatedProducts'));
    }

    public function suggestions(Request $request)
    {
        $keyword = $request->get('keyword', '');
        $suggestions = [];

        if (strlen($keyword) >= 2) {
            $products = Product::where('status', 'active')
                ->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                      ->orWhere('category', 'like', "%{$keyword}%")
                      ->orWhere('color', 'like', "%{$keyword}%")
                      ->orWhere('size', 'like', "%{$keyword}%");
                })
                ->select('name', 'category', 'color')
                ->limit(6)
                ->get();

            $seen = [];
            foreach ($products as $product) {
                if (!in_array($product->name, $seen)) {
                    $suggestions[] = ['label' => $product->name, 'type' => 'Product'];
                    $seen[] = $product->name;
                }
                if (!in_array($product->category, $seen)) {
                    $suggestions[] = ['label' => $product->category, 'type' => 'Category'];
                    $seen[] = $product->category;
                }
            }
        }

        return response()->json($suggestions);
    }
}
