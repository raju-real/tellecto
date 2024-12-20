<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Common\BrandController;
use App\Http\Controllers\Common\CategoryController;
use App\Http\Controllers\Common\ForgetPasswordController;
use App\Http\Controllers\Common\ProductController;
use App\Http\Controllers\Common\SubCategoryController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login')->name('login');
});
Route::post('business/register',[\App\Http\Controllers\Business\AuthController::class , 'businessRegister']);
Route::get('public/categories', [CategoryController::class, 'allCategory']);
Route::get('public/sub-categories', [SubCategoryController::class, 'index']);
Route::get('public/brands', [BrandController::class, 'allBrand']);
Route::get('public/hero-banners', [BrandController::class, 'allHeroBanner']);
Route::get('public/colors', [BrandController::class, 'allColor']);
Route::get('public/sizes', [BrandController::class, 'allSize']);
Route::get('public/types', [BrandController::class, 'allType']);
Route::get('public/active-products', [ProductController::class, 'getActiveProducts']);
Route::get('public/product-details/{slug}', [ProductController::class, 'productDetails']);
//Route::apiResource('public/brands', BrandController::class);
Route::get('public/product-by-brands/{brand_slug}', [ProductController::class, 'productByBrands']);
Route::get('public/brand-by-slug/{brand_slug}', [ProductController::class, 'brandBySlug']);
Route::post('public/send-message', [BrandController::class, 'sendPublicMessage']);

Route::get('logout', function () {
    //auth()->user()->tokens()->delete();
    Cache::flush();
    Session::flush();
    return response()->json([
        'status' => 'success',
        "message" => "Logged Out!"
    ]);
});

Route::get('change-b-pass', function () {
    foreach (\App\Models\Category::all() as $category) {
        $category = \App\Models\Category::find($category->id);
        $category->slug = \Illuminate\Support\Str::slug($category->name);
        $category->save();
    }

    foreach (\App\Models\SubCategory::all() as $category) {
        $category = \App\Models\SubCategory::find($category->id);
        $category->slug = \Illuminate\Support\Str::slug($category->name);
        $category->save();
    }
    return "done";
});

Route::controller(ForgetPasswordController::class)->group(function () {
    Route::post('send-password-reset-link', 'sendPasswordResetLink');
    Route::get('password-reset-form', 'passwordResetForm')->name('password-reset-form');
    Route::put('password-reset', 'resetPassword')->name('password-reset');
});



