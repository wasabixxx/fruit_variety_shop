<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $products = Product::with('category')->get();
    return view('welcome', compact('products'));
});

// Routes cho Category (CRUD)
Route::resource('categories', CategoryController::class);

// Routes cho Product (CRUD)
Route::resource('products', ProductController::class);


Route::get('/', function () {
    $products = Product::with('category')->paginate(9); // Pagination cho trang chủ, 9 sản phẩm/trang
    return view('welcome', compact('products'));
});