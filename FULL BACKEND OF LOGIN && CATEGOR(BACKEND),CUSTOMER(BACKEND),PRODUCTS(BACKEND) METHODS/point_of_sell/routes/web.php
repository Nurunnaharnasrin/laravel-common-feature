<?php

use App\Http\Controllers\ProductController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\TokenVerificationMiddleware;

// Web API Routes
Route::post('/user-registration',[UserController::class,'UserRegistration']);
Route::post('/user-login',[UserController::class,'UserLogin']);
Route::post('/send-otp',[UserController::class,'SendOTPCode']);
Route::post('/verify-otp',[UserController::class,'VerifyOTP']);
Route::post('/reset-password',[UserController::class,'ResetPassword'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/user-profile',[UserController::class,'UserProfile'])->middleware([TokenVerificationMiddleware::class]);
Route::post('/user-update',[UserController::class,'UserUpdate'])->middleware([TokenVerificationMiddleware::class]);



// User Logout
Route::get('/logout',[UserController::class,'UserLogout']);

// Page Routes
Route::get('/userLogin',[UserController::class,'LoginPage']);
Route::get('/userRegistration',[UserController::class,'RegistrationPage']);
Route::get('/sendOtp',[UserController::class,'SendOtpPage']);
Route::get('/verifyOtp',[UserController::class,'VerifyOTPPage']);
Route::get('/resetPassword',[UserController::class,'ResetPasswordPage'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/dashboard',[DashboardController::class,'DashboardPage'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/userProfile',[UserController::class,'ProfilePage'])->middleware([TokenVerificationMiddleware::class]);

//Customers routes
Route::post('/create-customer',[CustomerController::class,'CustomerCreate'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/list-customer',[CustomerController::class,'CustomerList'])->middleware([TokenVerificationMiddleware::class]);
Route::post('/delete-customer',[CustomerController::class,'CustomerDelete'])->middleware([TokenVerificationMiddleware::class]);
Route::post('/update-customer',[CustomerController::class,'CustomerUpdate'])->middleware([TokenVerificationMiddleware::class]);

//Customers routes
Route::post('/create-category',[CategoryController::class,'CategoryCreate'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/list-category',[CategoryController::class,'CategoryList'])->middleware([TokenVerificationMiddleware::class]);
Route::post('/delete-category',[CategoryController::class,'CategoryDelete'])->middleware([TokenVerificationMiddleware::class]);
Route::post('/update-category',[CategoryController::class,'CategoryUpdate'])->middleware([TokenVerificationMiddleware::class]);


//Products routes
Route::post('/create-product',[ProductController::class,'CreateProduct'])->middleware([TokenVerificationMiddleware::class]);
Route::post('/delete-product',[ProductController::class,'DeleteProduct'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/list-product',[ProductController::class,'UpdateList'])->middleware([TokenVerificationMiddleware::class]);
Route::post('/update-product',[ProductController::class,'UpdateProduct'])->middleware([TokenVerificationMiddleware::class]);


//dashboard api

Route::get('/total-customer',[DashboardController::class,'TotalCustomer'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/total-category',[DashboardController::class,'TotalCategory'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/total-product',[DashboardController::class,'TotalProduct'])->middleware([TokenVerificationMiddleware::class]);
