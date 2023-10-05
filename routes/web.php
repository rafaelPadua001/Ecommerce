<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Categories\CategoriesController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\ProductImages\ProductImagesController;
use App\Http\Controllers\Subcategories\SubcategoriesController;
use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\ProductSeo\ProductSeoController;
use App\Http\Controllers\ProductStock\ProductStockController;
use App\Http\Controllers\ProductVideos\ProductVideoController;
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
Route::post('/login',[LoginController::class, 'login'])->name('cutomer.login');
Route::post('/registerCustomer', [CustomerController::class, 'store'])->name('store');
Route::post('/logoutCustomer', [CustomerController::class, 'logout'])->name('logout');

//Categories routes
Route::get('/categories', [CategoriesController::class, 'index'])->name('categories.index')->middleware('auth');
Route::get('/categories/show', [CategoriesController::class, 'show'])->name('categories.show');

//Cart Routes
Route::get('/carts',[CartController::class, 'index'])->name('carts.get')->middleware('auth');

//Subcategories routes
Route::get('/subcategories', [SubcategoriesController::class, 'index'])->name('subcategories.index')->middleware('auth');
Route::get('/subcategories/all/{category_id}', [SubcategoriesController::class, 'all'])->name('sucategories.all');

//Product routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index')->middleware('auth');
Route::get('/products/show', [ProductController::class, 'index'])->name('products.show');

//SEO routes
Route::get('/seo', [ProductSeoController::class, 'index'])->name('seo.index')->middleware('auth');

//Stocks routes
Route::get('/stocks', [ProductStockController::class, 'index'])->name('stock.index')->middleware('auth');

Route::get('/images', [ProductImagesController::class, 'index'])->name('images.index')->middleware('auth');

//Videos routes
Route::get('/videos', [ProductVideoController::class, 'index'])->name('videos.index')->middleware('auth');


//Products routes
//Route::get('/products', function(){
//    dd('teste product routes');
//});