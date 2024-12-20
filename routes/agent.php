<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Agent\AuthController;
use App\Http\Controllers\Agent\CartController;
use App\Http\Controllers\Agent\ProfileController;
use App\Http\Controllers\Common\AddressController;
use App\Http\Controllers\Agent\AgentActivityController;
use App\Http\Controllers\Agent\ForgetPasswordController;

Route::controller(AuthController::class)->group(function () {
    Route::post('agent/login', 'agentLogin')->name('agent-login');
});

Route::prefix('agent')->middleware(['auth:agent'])->group(function () {
    Route::get('agent-profile', [ProfileController::class, 'agentProfile']);
    Route::put('update-agent-profile', [ProfileController::class, 'updateAgentProfile']);
    Route::put('change-agent-password', [ProfileController::class, 'changePassword']);
    Route::controller(AgentActivityController::class)->group(function () {
        Route::get('agent-products','agentProduct')->name('agent-products');
        Route::get('vat-policy','vatPolicy');
        Route::get('delivery-charges','deliveryCharges')->name('delivery-charges');
        Route::get('nearest-service-by-address','nearestServiceByAddress')->name('nearest-service-by-address');
        Route::get('agent-billing-address','billingAddress')->name('agent-billing-address');
        Route::post('submit-order','submitOrder')->name('submit-order');
        Route::get('agent-orders','orderList')->name('agent-orders');
        Route::get('agent-order-details/{order_id}','orderDetails')->name('agent-order-details');
        Route::get('agent-download-invoice/{order_id}','downloadInvoice')->name('agent-download-invoice');
    });
    Route::apiResource('address', AddressController::class);

    // Cart functionality
    Route::controller(CartController::class)->group(function() {
        Route::get('cart-lists','cartLists')->name('cart-lists');
        Route::post('add-to-cart','addToCart')->name('add-to-cart');
        Route::get('update-cart-quantity','updateCartQuantity')->name('update-cart-quantity');
        Route::delete('remove-item-from-cart','removeItemFromCart')->name('remove-item-from-cart');
        Route::delete('clear-cart','clearCart')->name('clear-cart');
    });
});

Route::controller(ForgetPasswordController::class)->group(function () {
    Route::post('agent/send-agent-password-reset-link','sendPasswordResetLink');
    Route::get('agent/agent-password-reset-form','passwordResetForm')->name('agent-password-reset-form');
    Route::put('agent/agent-password-reset','resetPassword')->name('agent-password-reset');
});




