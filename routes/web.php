<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerProductsController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/

Route::get(
    '/customer/products',
    [CustomerProductsController::class, 'index']
)->name('customer.products');

Route::get(
    '/customer/products/suggestions',
    [CustomerProductsController::class, 'suggestions']
)->name('customer.products.suggestions');

Route::get(
    '/customer/products/{product}',
    [CustomerProductsController::class, 'show']
)->name('customer.products.show');


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Product Search Suggestions
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/products/suggestions',
        [ProductController::class, 'suggestions']
    )->name('products.suggestions');


    /*
    |--------------------------------------------------------------------------
    | CSV Export
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/products/export',
        [ProductController::class, 'export']
    )->name('products.export');


    /*
    |--------------------------------------------------------------------------
    | Trash
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/products/trash',
        [ProductController::class, 'trash']
    )->name('products.trash');


    /*
    |--------------------------------------------------------------------------
    | Restore
    |--------------------------------------------------------------------------
    */

    Route::patch(
        '/products/{id}/restore',
        [ProductController::class, 'restore']
    )->name('products.restore');


    /*
    |--------------------------------------------------------------------------
    | Permanent Delete
    |--------------------------------------------------------------------------
    */

    Route::delete(
        '/products/{id}/force-delete',
        [ProductController::class, 'forceDelete']
    )->name('products.forceDelete');


    /*
    |--------------------------------------------------------------------------
    | Status Toggle
    |--------------------------------------------------------------------------
    */

    Route::patch(
        '/products/{product}/toggle-status',
        [ProductController::class, 'toggleStatus']
    )->name('products.toggleStatus');


    /*
    |--------------------------------------------------------------------------
    | Bulk Delete
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/products/bulk-delete',
        [ProductController::class, 'bulkDelete']
    )->name('products.bulk-delete');


    /*
    |--------------------------------------------------------------------------
    | Duplicate
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/products/{product}/duplicate',
        [ProductController::class, 'duplicate']
    )->name('products.duplicate');


    /*
    |--------------------------------------------------------------------------
    | Product CRUD
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'products',
        ProductController::class
    );


    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');
});


/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Welcome
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


require __DIR__ . '/auth.php';
