<?php

use App\Http\Controllers\AddressesController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Categories\CategoriesController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Cart\CartItemController;
use App\Http\Controllers\Likes\LikedProductController;
use App\Http\Controllers\Orders\OrderController;
use App\Http\Controllers\ProductImages\ProductImagesController;
use App\Http\Controllers\Subcategories\SubcategoriesController;
use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\ProductSeo\ProductSeoController;
use App\Http\Controllers\ProductStock\ProductStockController;
use App\Http\Controllers\ProductVideos\ProductVideoController;
use App\Http\Controllers\ProfileImage\ProfileImageController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Search\SearchToAddressesController;
use App\Http\Controllers\Coupons\CouponsController;
use App\Http\Controllers\Coupons\CouponCustomer\CouponCustomerController;
use App\Http\Controllers\Delivery\DeliveryController;
use App\Http\Controllers\Shippment\ShippmentController;
use App\Http\Controllers\Store\StoreController;
use Illuminate\Support\Facades\Auth;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
//admin

Route::get('/admin', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::post('/loginAdmin', [LoginController::class, 'login'])->name('login');
Route::post('/registerAdmin', [LoginController::class, 'register'])->name('admin.register');
Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
Route::get('/users', function(){
    $user = Auth::user();
    try{
        return response()->json($user);
    }
    catch(Exception $e){
        return response()->json($e);
    }
   
});


//Customer Routes
Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
Route::post('/customer/update/{id}', [CustomerController::class, 'update'])->name('customer.update');
Route::post('/login',[LoginController::class, 'login'])->name('cutomer.login');
Route::post('/registerCustomer', [CustomerController::class, 'store'])->name('store');
Route::post('/logoutCustomer', [CustomerController::class, 'logout'])->name('logout');
Route::delete('/customer/delete/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');

//Customer Profile Image
Route::get('/profileImage', [ProfileImageController::class, 'index'])->name('profile.image.index');
Route::post('/profileImage/upload', [ProfileImageController::class, 'upload'])->name('profile.image.upload');
Route::delete('/profileImage/delete/{id}', [ProfileImageController::class, 'destroy'])->name('profile.image.destroy');

//Customer Addresses
Route::get('/address', [AddressesController::class, 'index'])->name('address.index');
Route::post('/address/save', [AddressesController::class, 'create'])->name('address.create');
Route::post('/address/update/{id}', [AddressesController::class, 'update'])->name('address.update');
Route::delete('/address/delete/{id}', [AddressesController::class, 'destroy'])->name('address.delete');

//Categories routes
Route::get('/categories', [CategoriesController::class, 'index'])->name('categories.index');
Route::get('/categories/show', [CategoriesController::class, 'show'])->name('categories.show');
Route::post('/categories/store/{id}', [CategoriesController::class, 'create'])->name('create');
//Cart Routes
Route::get('/carts',[CartController::class, 'index'])->name('carts.get');
Route::post('/carts/add',[CartController::class, 'addItem'])->name('carts.get');

Route::get('/cartItem/checkout/{id}', [CartItemController::class, 'checkout'])->name('cartsItem.checkout');
Route::get('/cartItem/buy', [CartItemController::class, 'buy'])->name('cartsItem.buy');
Route::delete('/cartItem/delete/{id}', [CartItemController::class, 'destroy'])->name('cartsItem.destroy');

//Delivery Routes
Route::get('/delivery', [DeliveryController::class, 'index'])->name('delivery')->middleware('auth');
Route::post('/delivery/insert', [DeliveryController::class, 'store'])->name('delivery.store')->middleware('auth');
Route::post('/delivery/status/{id}', [DeliveryController::class, 'status'])->name('delivery.status')->middleware('auth');
Route::delete('/delivery/delete/{id}', [DeliveryController::class, 'delete'])->name('delivery.delete')->middleware('auth');

//Shippments Routes
Route::get('/shippments', [ShippmentController::class, 'index'])->name('shippments.index')->middleware('auth');
Route::post('/shippments/update/{id}', [ShippmentController::class, 'update'])->name('shippments.upate')->middleware('auth');
Route::delete('/shippments/delete/{id}', [ShippmentController::class, 'remove'])->name('shippments.remove')->middleware('auth');
//Payment Routes
Route::post('/payment', [PaymentController::class, 'payment'])->name('payment');

//Subcategories routes
Route::get('/subcategories', [SubcategoriesController::class, 'index'])->name('subcategories.index')->middleware('auth');
Route::get('/subcategories/all/{category_id}', [SubcategoriesController::class, 'all'])->name('sucategories.all');

//Product routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index')->middleware('auth');
Route::get('/products/show', [ProductController::class, 'show'])->name('products.show');
Route::post('/products/like/{id}', [ProductController::class , 'like'])->name('product.like');
Route::delete('/products/dislike/{id}', [ProductController::class, 'dislike'])->name('product.dislike');

//Likes Route

Route::get('/likes', [LikedProductController::class, 'index'])->name('likes.index');
Route::delete('/likes/delete/{id}', [LikedProductController::class, 'destroy'])->name('likes.destroy');

//SEO routes
Route::get('/seo', [ProductSeoController::class, 'index'])->name('seo.index')->middleware('auth');



//Stocks routes
Route::get('/stocks', [ProductStockController::class, 'index'])->name('stock.index')->middleware('auth');

Route::get('/images', [ProductImagesController::class, 'index'])->name('images.index')->middleware('auth');

//Videos routes
Route::get('/videos', [ProductVideoController::class, 'index'])->name('videos.index')->middleware('auth');

//Search cep to address
Route::post('/saveSearchAddress', [SearchToAddressesController::class, 'save'])->name('cep.search');

//Route Orders
Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
Route::get('/allOrders', [OrderController::class, 'allOrders'])->name('allOrders')->middleware('auth');
Route::get('/orders/getOrder/{id}', [OrderController::class, 'getOrder'])->name('getOrder')->middleware('auth');
Route::get('/orders/getTransaction/{id}', [OrderController::class, 'getTransaction'])->name('getTransaction')->middleware('auth');
Route::put('/orders/refundTransaction/{id}',[OrderController::class, 'refundTransaction'])->name('refund')->middleware('auth');
//Route::post('/orders/generatePdf/{id}', [OrderController::class, 'generatePdf'])->name('generatePdf')->middleware('auth');
Route::post('/orders/alterStatus/{id}', [OrderController::class, 'alterStatus'])->name('order.alterStatus')->middleware('auth');
//Coupons Routes
Route::post('/coupons/add', [CouponsController::class, 'create'])->name('coupon.add')->middleware('auth');
Route::get('/coupons/all', [CouponsController::class, 'index'])->name('coupon.index')->middleware('auth');
Route::get('/coupons/getWelcome', [CouponsController::class, 'getInitDiscount'])->name('coupon.welcome');
Route::post('/coupons/getCoupon/{id}', [CouponCustomerController::class, 'getCoupon'])->name('coupon.getCoupon');
Route::post('/coupons/update/{id}', [CouponsController::class, 'update'])->name('coupon.update')->middleware('auth');
Route::delete('/coupons/delete/{id}', [CouponsController::class, 'destroy'])->name('coupon.destroy')->middleware('auth');
 
//Route Coupons to Customer
Route::get('/couponsCustomer', [CouponCustomerController::class, 'getAll'])->name('coupon.all');
Route::delete('/couponsCustomer/delete/{id}', [CouponCustomerController::class, 'remove'])->name('coupon.remove');

//Route Store
Route::get('/store', [StoreController::class, 'index'])->name('store.index');
Route::post('/store/create', [StoreController::class, 'create'])->name('store.create')->middleware('auth');
Route::post('/store/update/{id}', [StoreController::class, 'update'])->name('store.update')->middleware('auth');
Route::get('/store/style/getStyle/{id}', [StoreController::class, 'getStyle'])->name('store.get.style')->middleware('auth');
Route::post('/store/style/create', [StoreController::class, 'styleCreate'])->name('store.style.create')->middleware('auth');
Route::post('/store/style/update/{storeId}', [StoreController::class, 'styleUpdate'])->name('store.style.update')->middleware('auth');
Route::delete('/store/style/remove/{storeId}', [StoreController::class, 'remove'])->name('store.style.remove')->middleware('auth');
Route::delete('/store/delete/{id}', [StoreController::class, 'delete'])->name('store.delete')->middleware('auth');


//Products routes
Route::post('/products/store', [ProductController::class, 'store'])->name('products.store')->middleware('auth');
//Route::get('/products', function(){s
//    dd('teste product routes');
//});