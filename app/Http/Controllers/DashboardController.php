<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $outOfStockProducts = Product::where('stock', '<=', 0)->count();
        $lowStockProducts = Product::where('stock', '>', 0)
            ->where('stock', '<=', DB::raw('low_stock_threshold'))
            ->count();

        $activeProducts = Product::where('status', 'active')->count();

        $categoryStats = Product::select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->orderByDesc('count')
            ->get();

        $recentProducts = Product::latest()->take(5)->get();

        $productsByMonth = Product::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('year', 'month')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->take(6)
            ->get()
            ->reverse()
            ->values();

        return view('dashboard', compact(
            'totalProducts',
            'outOfStockProducts',
            'lowStockProducts',
            'activeProducts',
            'categoryStats',
            'recentProducts',
            'productsByMonth'
        ));
    }
}
