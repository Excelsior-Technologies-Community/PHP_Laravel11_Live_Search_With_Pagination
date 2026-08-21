<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Dashboard Statistics
        |--------------------------------------------------------------------------
        */

        $totalProducts = Product::count();

        $outOfStockProducts = Product::where('stock', '<=', 0)->count();

        $lowStockProducts = Product::where('stock', '>', 0)
            ->where('stock', '<=', DB::raw('low_stock_threshold'))
            ->count();

        $activeProducts = Product::where('status', 'active')->count();


        /*
        |--------------------------------------------------------------------------
        | Category Statistics
        |--------------------------------------------------------------------------
        */

        $categoryStats = Product::select(
                'category',
                DB::raw('count(*) as count')
            )
            ->groupBy('category')
            ->orderByDesc('count')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Products Added By Month
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | Dashboard Product Search / Filters
        |--------------------------------------------------------------------------
        */

        $productsQuery = Product::query();


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('keyword')) {

            $keyword = trim($request->keyword);

            $productsQuery->where(function ($query) use ($keyword) {

                $query->where('name', 'like', "%{$keyword}%")
                    ->orWhere('category', 'like', "%{$keyword}%")
                    ->orWhere('color', 'like', "%{$keyword}%")
                    ->orWhere('size', 'like', "%{$keyword}%")
                    ->orWhere('details', 'like', "%{$keyword}%");

                /*
                |--------------------------------------------------------------------------
                | Allow price search
                |--------------------------------------------------------------------------
                */

                if (is_numeric($keyword)) {
                    $query->orWhere('price', (float) $keyword);
                }
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Category Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('category')) {

            $productsQuery->where(
                'category',
                $request->category
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $productsQuery->where(
                'status',
                $request->status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Stock Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('stock_status')) {

            switch ($request->stock_status) {

                case 'in-stock':

                    $productsQuery
                        ->where('stock', '>', 0)
                        ->whereColumn(
                            'stock',
                            '>',
                            'low_stock_threshold'
                        );

                    break;


                case 'low-stock':

                    $productsQuery
                        ->where('stock', '>', 0)
                        ->whereColumn(
                            'stock',
                            '<=',
                            'low_stock_threshold'
                        );

                    break;


                case 'out-stock':

                    $productsQuery->where(
                        'stock',
                        '<=',
                        0
                    );

                    break;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        if ($request->filled('sort')) {

            switch ($request->sort) {

                case 'price-asc':

                    $productsQuery->orderBy(
                        'price',
                        'asc'
                    );

                    break;


                case 'price-desc':

                    $productsQuery->orderBy(
                        'price',
                        'desc'
                    );

                    break;


                case 'name-asc':

                    $productsQuery->orderBy(
                        'name',
                        'asc'
                    );

                    break;


                case 'name-desc':

                    $productsQuery->orderBy(
                        'name',
                        'desc'
                    );

                    break;


                case 'oldest':

                    $productsQuery->orderBy(
                        'created_at',
                        'asc'
                    );

                    break;


                default:

                    $productsQuery->latest();

                    break;
            }

        } else {

            $productsQuery->latest();

        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $dashboardProducts = $productsQuery
            ->paginate(5)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Categories For Filter
        |--------------------------------------------------------------------------
        */

        $categories = Product::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');


        /*
        |--------------------------------------------------------------------------
        | Dashboard AJAX Request
        |--------------------------------------------------------------------------
        |
        | Important:
        | We are NOT using a separate partial Blade file.
        | The complete dashboard view is returned and JavaScript
        | extracts the required section from it.
        |
        */

        return view('dashboard', compact(
            'totalProducts',
            'outOfStockProducts',
            'lowStockProducts',
            'activeProducts',
            'categoryStats',
            'productsByMonth',
            'dashboardProducts',
            'categories'
        ));
    }
}