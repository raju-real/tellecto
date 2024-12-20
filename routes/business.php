<?php

use App\Http\Controllers\Business\AgentController;
use App\Http\Controllers\Business\DashboardController;
use App\Http\Controllers\Business\OrderController;
use App\Http\Controllers\Business\ProductController;
use App\Http\Controllers\Business\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// Admin Authentication
Route::controller(\App\Http\Controllers\Business\AuthController::class)->group(function () {
    Route::post('business/login', 'businessLogin')->name('business-login');


});

// Admin routes
Route::prefix('business')->middleware(['auth:sanctum', 'business'])->group(function () {
     Route::controller(DashboardController::class)->group(function () {
        Route::get('business-dashboard','dashboard')->name('business-dashboard');
    });
    // Agent
    Route::apiResource('agents', AgentController::class);
    Route::get('agent-list', [AgentController::class,'agentList'])->name('agent-list');
    Route::controller(ProductController::class)->group(function () {
        Route::get('business-fetch-products', 'fetchProduct')->name('business-fetch-products');
        Route::get('business-update-products', 'updateProduct')->name('business-update-products');
        Route::get('business-product-lists', 'productLists')->name('business-product-lists');
        Route::get('show-product-by-id/{id}', 'showProductByIdForBusiness')->name('show-product-by-id');
        // Profit
        Route::put('set-business-products-profit-global', 'setProductProfitGlobal')->name('set-business-products-profit-global');
        Route::put('set-business-product-wise-profit', 'setProductWiseProfit')->name('set-business-product-wise-profit');
        Route::put('set-business-single-product-profit', 'setSingleProductProfit')->name('set-business-single-product-profit');
        Route::get('business-profile', [ProfileController::class, 'businessProfile']);
        Route::put('update-business-profile', [ProfileController::class, 'updateBusinessProfile']);
        // Order
        Route::controller(OrderController::class)->group(function () {
            Route::get('order-status-list','orderStatusList')->name('order-status-list');
            Route::get('list-order', 'orderList')->name('list-order');
            Route::get('confirm-order/{order_id}', 'confirmOrder')->name('confirm-order');
            Route::get('cancel-order/{order_id}', 'cancelOrder')->name('cancel-order');
            Route::get('details-order/{order_id}', 'orderDetails')->name('details-order');
            Route::get('business-download-invoice/{order_id}', 'downloadInvoice')->name('business-download-invoice');
        });
    });
});
