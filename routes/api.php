<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Categories\CategoriesController;
use App\Http\Controllers\ProductImages\ProductImagesController;
use App\Http\Controllers\Subcategories\SubcategoriesController;
use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\ProductSeo\ProductSeoController;
use App\Http\Controllers\ProductStock\ProductStockController;
use App\Http\Controllers\ProductVideos\ProductVideoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//Categories routes

Route::post('/categories/store/{id}', [CategoriesController::class, 'store'])->name('store');
Route::post('/categories/update/{id}', [CategoriesController::class, 'update'])->name('categories.update');
Route::delete('/categories/delete/{id}', [CategoriesController::class, 'destroy'])->name('categories.destroy');

//Subcategories route
Route::post('/subcategories/store/{id}', [SubcategoriesController::class, 'store'])->name('store');
Route::post('/subcategories/update/{id}', [SubcategoriesController::class, 'update'])->name('update');
Route::delete('/subcategories/delete/{id}', [SubcategoriesController::class, 'destroy'])->name('destroy');

//Products route
Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
Route::post('/products/update/{id}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/delete/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

//SEO route
Route::post('/seo_product/update/{id}', [ProductSeoController::class, 'update'])->name('seo_product.update');

//Stocks route
Route::post('/stocks/update/{id}', [ProductStockController::class, 'update'])->name('seo_product.update');

//Images route
Route::delete('/images/delete/{id}', [ProductImagesController::class, 'destroy'])->name('image.destroy');

//Video route
Route::delete('/videos/delete/{id}', [ProductVideoController::class, 'destroy'])->name('videos.destroy');