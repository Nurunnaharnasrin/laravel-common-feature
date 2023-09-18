<?php

use App\Http\Controllers\Backend\CuponController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\TestimonialController;
use App\Http\Controllers\Frontend\Auth\RegisterController;
use App\Http\Controllers\Frontend\CustomerController;

// Route::get('/', function () {
//     return view('frontend.pages.home');
// });

Route::prefix('')->group(function(){
    Route::get('/', [HomeController::class,'home'])->name('home');
    Route::get('/shop', [HomeController::class,'shopPage'])->name('shop.page');
    Route::get('/single-product/{product_slug}', [HomeController::class,'productDetails'])->name('productdetails.page');
    Route::get('/shopping-cart',[CartController::class,'cartPage'])->name('cart.page');
    Route::post('/add-to-cart',[CartController::class,'addToCart'])->name('add-to.cart');
    // Route::get('/add-to-cart/{product_slug}',[CartController::class,'addToCart'])->name('add-to.cart');
    Route::get('/remove-from-cart/{cart_id}', [CartController::class, 'removeFromCart'])->name('removefrom.cart');



    //Authorization routes for Customer/Guest
    Route::get('/register',[RegisterController::class,'registerPage'])->name('register.page');
    Route::post('/register',[RegisterController::class,'registerStore'])->name('register.store');
    Route::get('/login',[RegisterController::class,'loginPage'])->name('login.page');
    Route::post('/login',[RegisterController::class,'loginStore'])->name('login.store');


    Route::prefix('customer/')->middleware('auth')->group(function(){
        Route::get('dashboard',[CustomerController::class,'dashboard'])->name('customer.dashboard');
        Route::get('logout',[RegisterController::class,'logout'])->name('customer.logout');

    });
});

//============ Admin Auth route==============
Route::prefix('admin/')->group(function(){
    Route::get('login',[LoginController::class, 'loginPage'])->name('admin.loginpage');
    Route::post('login',[LoginController::class, 'login'])->name('admin.login');
    Route::get('logout',[LoginController::class, 'logout'])->name('admin.logout');

    Route::middleware(['auth'])->group(function(){
        Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');
    });
    //  Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');


    //======Resource Controller======
    Route::resource('category', CategoryController::class);
    Route::resource('testimonial', TestimonialController::class);
    Route::resource('products', ProductController::class);
    Route::resource('cupon', CuponController::class);

});
