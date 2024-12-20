<?php

use App\Http\Controllers\Business\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\BusinessController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HeroBannerController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\RolePermission\RoleController;
use App\Http\Controllers\Admin\ShippingMethodController;
use App\Http\Controllers\RolePermission\ActionController;
use App\Http\Controllers\RolePermission\AuthRoleController;
use App\Http\Controllers\RolePermission\PermissionController;

//business Auth
Route::patch('admin/business/{id}/status', [AuthController::class, 'updateStatus'])->middleware(['auth:api', 'admin']);
// Admin Authentication
Route::controller(AdminAuthController::class)->group(function () {
    Route::post('admin/login', 'adminLogin')->name('admin-login');
});

Route::prefix('admin')->middleware(['auth:api', 'admin'])->group(function () {

    Route::controller(DashboardController::class)->group(function () {
        Route::get('admin-dashboard','dashboard')->name('admin-dashboard');
    });

    // Role Permission
    Route::prefix('role-permission')->group(function () {
        Route::apiResource('role', RoleController::class);
        Route::get('all-role', [RoleController::class, 'allRoles']);
        Route::apiResource('action', ActionController::class);
        Route::get('all-action', [ActionController::class, 'allActions']);
        Route::apiResource('permission', PermissionController::class);
        Route::get('auth', [AuthRoleController::class, 'index']);
    });

    // Product
    Route::controller(ProductController::class)->group(function () {
        Route::get('product-list', 'productList')->name('product-list');
        Route::get('get-active-products', 'getActiveProducts')->name('get-active-products');
        Route::get('fetch-products', 'fetchProduct')->name('fetch-products');
        Route::get('save-products', 'saveProduct')->name('save-products');
        Route::get('update-products', 'updateProduct')->name('update-products');
        Route::get('show-product-details/{product_number}', 'productByNumber')->name('show-product-details');
        Route::get('product-by-id/{id}', 'productById')->name('product-by-id');
        Route::get('product-by-slug/{slug}', 'productBySlug')->name('product-by-slug');
        Route::delete('remove-product-images', 'removeProductImages')->name('remove-product-images');
        Route::put('single-product-update/{id}', 'singleProductUpdate')->name('single-product-update');
        /**
         * Old routes
         * Route::put('set-bulk-profit','setBulkProfit')->name('set-bulk-profit');
         * Route::put('set-manual-profit','setManualProfit')->name('set-manual-profit');
         * Route::put('set-bulk-status','setBulkStatus')->name('set-bulk-status');
         * Route::put('set-manual-status','setManualStatus')->name('set-manual-status');
         * Route::put('set-bulk-category','setBulkCategory')->name('set-bulk-category');
         * Route::put('set-manual-category','setManualCategory')->name('set-manual-category');
         */
        // Product profit
        Route::put('set-products-profit-global', 'setProductProfitGlobal')->name('set-products-profit-global');
        Route::put('set-product-wise-profit', 'setProductWiseProfit')->name('set-product-wise-profit');
        Route::put('set-single-product-profit', 'setSingleProductProfit')->name('set-single-product-profit');
        // Product status
        Route::put('set-products-status-global', 'setProductStatusGlobal')->name('set-products-status-global');
        Route::put('set-product-wise-status', 'setProductWiseStatus')->name('set-product-wise-status');
        Route::put('set-single-product-status', 'setSingleProductStatus')->name('set-single-product-status');
        // Product category
        Route::put('set-products-category-global', 'setProductCategoryGlobal')->name('set-products-category-global');
        Route::put('set-product-wise-category', 'setProductWiseCategory')->name('set-product-wise-category');
        Route::put('set-single-product-category', 'setSingleProductCategory')->name('set-single-product-category');
        // Description
        Route::put('set-product-description', 'setProductDescription')->name('set-product-description');
        Route::post('set-product-image', 'setProductImage')->name('set-product-image');
        Route::get('download-products-as-xl', 'downloadProductAsXl')->name('download-products-as-xl');
        Route::post('update-products-from-xl', 'updateProductFromXl')->name('update-products-from-xl');
    });

    Route::controller(OrderController::class)->group(function () {
        Route::get('order-status-list','orderStatusList')->name('order-status-list');
        Route::get('order-list','orderList')->name('order-list');
        Route::get('order-details/{order_id}', 'orderDetails')->name('order-details');
        Route::get('approve-order/{order_id}','approveOrder')->name('approve-order');
        Route::get('shipping-methods-orders','shippingMethods')->name('shipping-methods-orders');
        Route::put('change-delivery-option/{order_id}','changeDeliveryOption')->name('change-delivery-option');
        Route::get('decline-order/{order_id}','declineOrder')->name('decline-order');
        Route::get('order-logs/{order_id}','orderLogs')->name('order-logs');
        Route::get('reserve-order','reserveOrder')->name('reserve-order');
        Route::get('track-order','trackOrder')->name('track-order');
        Route::get('download-admin-invoice/{order_id}', 'downloadAdminInvoice')->name('download-admin-invoice');
        Route::get('download-business-invoice/{order_id}', 'downloadBusinessInvoice')->name('download-business-invoice');
        Route::get('download-agent-invoice/{order_id}', 'downloadAgentInvoice')->name('download-agent-invoice');
    });

    //Others

    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('colors', ColorController::class);
    Route::get('all-colors', [ColorController::class,'allColor']);
    Route::apiResource('sizes', SizeController::class);
    Route::get('all-sizes', [SizeController::class,'allSize']);
    Route::apiResource('types', TypeController::class);
    Route::get('all-types', [TypeController::class,'allType']);

    Route::get('get-category-by-slug/{slug}', [CategoryController::class, 'getCategoryBySlug'])->name('get-category-by-slug');
    Route::put('set-category-status-global', [CategoryController::class, 'setCategoryStatusGlobal'])->name('set-category-status-global');
    Route::apiResource('sub-categories', SubCategoryController::class);
    Route::put('set-subcategory-status-global', [SubCategoryController::class, 'setSubCategoryStatusGlobal'])->name('set-subcategory-status-global');

    Route::get('get-subcategory-by-slug/{slug}', [SubCategoryController::class, 'getSubcategoryBySlug'])->name('get-subcategory-by-slug');
    Route::apiResource('brands', BrandController::class);

    Route::get('get-brand-by-slug/{slug}', [BrandController::class, 'getBrandBySlug'])->name('get-brand-by-slug');
    Route::put('set-brand-status-global', [BrandController::class, 'setBrandStatusGlobal'])->name('set-brand-status-global');
    Route::apiResource('admins', AdminController::class);
    Route::put('change-admin-status/{id}', [AdminController::class,'changeAdminStatus'])->name('change-admin-status');
    Route::apiResource('admin-roles', AdminRoleController::class);
    Route::apiResource('businesses', BusinessController::class);
    Route::put('change-business-status/{id}', [BusinessController::class,'changeBusinessStatus'])->name('change-business-status');
    Route::put('change-business-password/{id}', [BusinessController::class,'changeBusinessPassword'])->name('change-business-password');
    Route::apiResource('agents', AgentController::class);
    Route::get('all-agent', [AgentController::class,'allAgent']);
    Route::put('change-agent-password/{id}', [AgentController::class,'changeAgentPassword'])->name('change-agent-password');
    Route::get('all-businesses', [BusinessController::class,'allBusinesses']);
    Route::get('admin-profile', [ProfileController::class, 'adminProfile']);
    Route::put('update-admin-profile', [ProfileController::class, 'updateAdminProfile']);
    Route::apiResource('hero-banners', HeroBannerController::class);
    Route::apiResource('shipping-methods', ShippingMethodController::class);
    Route::get('all-shipping-methods', [ShippingMethodController::class,'allShippingMethod']);
});


Route::middleware('auth:sanctum')->prefix('common')->group(function () {
    Route::get('all-category', [CategoryController::class, 'allCategory']);
    Route::get('all-sub-category/{id}', [SubCategoryController::class, 'getAllSubCategoryByCatId']);
    Route::get('all-brand', [BrandController::class, 'allBrand']);
    Route::get('get-order-status',function () {
        return \App\Models\Order::orderStatusList();
    });
});
