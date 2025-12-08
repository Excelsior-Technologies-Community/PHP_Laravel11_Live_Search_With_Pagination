<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Show all products with search + sort + pagination
    public function index(Request $request)
    {
        $query = Product::where('status', '!=', 'deleted');

        // LIVE SEARCH
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

        // SORTING
        if ($request->filled('sort') && in_array($request->sort, ['price-asc', 'price-desc'])) {
            $query->orderBy('price', $request->sort == 'price-asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        // PAGINATION
        $products = $query->paginate(1)->appends($request->query());

        return view('products.index', compact('products'));
    }

    // Create page
    public function create()
    {
        return view('products.create');
    }

    // Store product
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'details'   => 'required',
            'size'      => 'required',
            'color'     => 'required',
            'category'  => 'required',
            'price'     => 'required|numeric',
            'image'     => 'required|image|max:2048',
        ]);

        // UPLOAD SINGLE IMAGE
        $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        Product::create([
            'name'      => $request->name,
            'details'   => $request->details,
            'size'      => $request->size,
            'color'     => $request->color,
            'category'  => $request->category,
            'price'     => $request->price,
            'image'     => 'images/' . $imageName,
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    // Edit Product
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    // Update Product
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'      => 'required',
            'details'   => 'required',
            'size'      => 'required',
            'color'     => 'required',
            'category'  => 'required',
            'price'     => 'required|numeric',
            'image'     => 'nullable|image|max:2048',
        ]);

        $imagePath = $product->image;

        // If new image uploaded
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
            'image'     => $imagePath,
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    // Soft Delete
    public function destroy(Product $product)
    {
        
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
