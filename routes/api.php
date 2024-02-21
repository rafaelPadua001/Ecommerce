<?php

use App\Http\Controllers\AppBar\AppBarController;
use App\Http\Controllers\Banner\BannerController;
use App\Http\Controllers\Card\CardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Categories\CategoriesController;
use App\Http\Controllers\MelhorEnvio\MelhorEnvioController;
use App\Http\Controllers\ProductImages\ProductImagesController;
use App\Http\Controllers\Subcategories\SubcategoriesController;
use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\ProductSeo\ProductSeoController;
use App\Http\Controllers\ProductStock\ProductStockController;
use App\Http\Controllers\ProductVideos\ProductVideoController;
use App\Http\Controllers\Coupons\CouponsController;
use App\Http\Controllers\Delivery\DeliveryController;
use App\Http\Controllers\Comments\CommentsController;
use App\Http\Controllers\Coupons\CouponCustomer\CouponCustomerController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Store\StoreController;
use App\Services\CarrouselService\CarrouselService;

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

//Route AppBar
Route::get('/appBar', [AppBarController::class, 'index'])->name('appBar.index');

//Route Banner
Route::get('/banner', [BannerController::class, 'index'])->name('banner.index');

//Route Cards Products
Route::get('/card', [CardController::class, 'index'])->name('card.index');

//Carrousel Routes
Route::get('/carrousel', [CarrouselService::class, 'getAttributes'])->name('carrousel.index');

//Customer Routes
Route::get('/getCustomer', [CustomerController::class, 'index'])->name('customer.getCustomer');

//Categories routes
Route::post('/categories/store/{id}', [CategoriesController::class, 'store'])->name('store');
Route::post('/categories/update/{id}', [CategoriesController::class, 'update'])->name('categories.update');
Route::delete('/categories/delete/{id}', [CategoriesController::class, 'destroy'])->name('categories.destroy');

//Route coupons
Route::get('/coupons/all', [CouponsController::class, 'index'])->name('coupon.index');
Route::get('/coupons/getOthers', [CouponsController::class, 'others'])->name('coupon.others');



//Subcategories route
Route::post('/subcategories/store/{id}', [SubcategoriesController::class, 'store'])->name('store');
Route::post('/subcategories/update/{id}', [SubcategoriesController::class, 'update'])->name('update');
Route::delete('/subcategories/delete/{id}', [SubcategoriesController::class, 'destroy'])->name('destroy');

//Products route
#Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
Route::post('/products/update/{id}', [ProductController::class, 'update'])->name('products.update');
Route::post('/products/search/{id}', [ProductController::class, 'getProduct'])->name('product.search');

//Route::post('/products/update/{id}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/delete/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

//Store Routes
Route::get('/store/getAppIcon', [StoreController::class, 'getAppIcon'])->name('store.getStore');

//Delivery Routes
Route::get('/delivery', [DeliveryController::class, 'index'])->name('delivery.index');

Route::get('/comment', [CommentsController::class, 'index'])->name('comment.index');
Route::post('/comment/create', [CommentsController::class, 'create'])->name('comment.create');
Route::delete('/comment/delete/{id}', [CommentsController::class, 'remove'])->name('comment.remove');

//SEO route
Route::post('/seo_product/update/{id}', [ProductSeoController::class, 'update'])->name('seo_product.update');
Route::delete('/seo_product/delete/{id}', [ProductSeoController::class, 'destroy'])->name('seo_product.delete');
//Stocks route
Route::post('/stocks/update/{id}', [ProductStockController::class, 'update'])->name('seo_product.update');

//Images route
Route::delete('/images/delete/{id}', [ProductImagesController::class, 'destroy'])->name('image.destroy');
Route::delete('/images/deleteAll/{id}',[ProductImagesController::class, 'destroyAll'])->name('allImages.destroy');

//Video route
Route::delete('/videos/delete/{id}', [ProductVideoController::class, 'destroy'])->name('videos.destroy');

//Melhor envio
Route::post('/calculateDelivery', [DeliveryController::class, 'calculateDelivery'])->name('calculate.delivery');
#Route::post('/calculateDelivery', [MelhorEnvioController::class, 'calculateDelivery'])->name('calculate.delivery');
Route::post('/generateTicket', [MelhorEnvioController::class, 'generatePrint'])->name('generateTicket.delivery');
Route::post('/getTicket', [MelhorEnvioController::class, 'getPrint'])->name('generateTicket.getPrint');
Route::post('/melhorenvio/checkout', [MelhorEnvioController::class, 'checkout'])->name('checkout.delivery');
Route::post('/melhorenvio/tracking', [MelhorEnvioController::class, 'tracking'])->name('checkout.tracking');
Route::post('/melhorenvio/delete/{id}', [MelhorEnvioController::class, 'destroy'])->name('checkout.destroy');