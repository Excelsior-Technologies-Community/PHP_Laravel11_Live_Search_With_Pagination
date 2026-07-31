<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerProductsController;

Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class)->except(['show']);
    Route::get('/products/suggestions', [ProductController::class, 'suggestions'])->name('products.suggestions');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::get('/customer/products', [CustomerProductsController::class, 'index'])->name('customer.products');
Route::get('/customer/products/suggestions', [CustomerProductsController::class, 'suggestions'])->name('customer.products.suggestions');
Route::get('/customer/products/{product}', [CustomerProductsController::class, 'show'])->name('customer.products.show');

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
