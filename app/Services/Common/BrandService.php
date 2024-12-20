<?php

namespace App\Services\Common;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\HeroBanner;
use App\Models\Size;
use App\Models\SubCategory;
use App\Models\Type;
use Illuminate\Http\JsonResponse;

/**
 * Class BrandService.
 */
class BrandService
{
    public function allBrand()
    {
        $query = Brand::select('brands.id', 'brands.name', 'brands.slug', 'brands.thumbnail', 'brands.original', 'brands.active_status')
            ->join('products', 'brands.id', '=', 'products.brand_id')
            ->where('brands.active_status',1)
            ->where('brands.name', '!=', 'Non brand')
            ->distinct();

        // Apply category filter if present
        if ($categorySlugs = request()->get('category')) {
            $categoryIds = Category::whereIn('slug', explode(',', $categorySlugs))->pluck('id');
            $query->whereIn('products.category_id', $categoryIds);
        }

        // Apply subcategory filter if present
        if ($subcategorySlugs = request()->get('subcategory')) {
            $subcategoryIds = SubCategory::whereIn('slug', explode(',', $subcategorySlugs))->pluck('id');
            $query->whereIn('products.subcategory_id', $subcategoryIds);
        }

        $query->orderBy('brands.name');
        // Execute the query and get the filtered or all brands
        $data = $query->get();

        return apiResponse('success', 200, $data);
    }


    public function allColor()
    {
        $query = Color::select('colors.id', 'colors.color_name', 'colors.color_code', 'colors.slug')
            ->join('product_colors', 'colors.id', '=', 'product_colors.color_id')
            ->join('products', 'product_colors.product_id', '=', 'products.id');

        // Apply category filter if present
        if (request()->has('category')) {
            $categorySlugs = explode(',', request()->get('category'));
            $categoryIds = Category::whereIn('slug', array_map('trim', $categorySlugs))->pluck('id');
            $query->whereIn('products.category_id', $categoryIds);
        }

        // Apply subcategory filter if present
        if (request()->has('subcategory')) {
            $subcategorySlugs = explode(',', request()->get('subcategory'));
            $subcategoryIds = SubCategory::whereIn('slug', array_map('trim', $subcategorySlugs))->pluck('id');
            $query->whereIn('products.subcategory_id', $subcategoryIds);
        }

        // Apply brand filter if present
        if (request()->has('brand')) {
            $brandSlugs = explode(',', request()->get('brand'));
            $brandIds = Brand::whereIn('slug', array_map('trim', $brandSlugs))->pluck('id');
            $query->whereIn('products.brand_id', $brandIds);
        }

        // Get the filtered or all colors
        $data = $query->orderBy('colors.color_name')
            ->distinct()
            ->get();

        return apiResponse('success', 200, $data);
    }

    public function allSize()
    {
        $query = Size::select('sizes.id', 'sizes.size_name', 'sizes.slug')
            ->join('product_sizes', 'sizes.id', '=', 'product_sizes.size_id')
            ->join('products', 'product_sizes.product_id', '=', 'products.id');

        // Apply category filter if present
        if (request()->has('category')) {
            $categorySlugs = explode(',', request()->get('category'));
            $categoryIds = Category::whereIn('slug', array_map('trim', $categorySlugs))->pluck('id');
            $query->whereIn('products.category_id', $categoryIds);
        }

        // Apply subcategory filter if present
        if (request()->has('subcategory')) {
            $subcategorySlugs = explode(',', request()->get('subcategory'));
            $subcategoryIds = SubCategory::whereIn('slug', array_map('trim', $subcategorySlugs))->pluck('id');
            $query->whereIn('products.subcategory_id', $subcategoryIds);
        }

        // Apply brand filter if present
        if (request()->has('brand')) {
            $brandSlugs = explode(',', request()->get('brand'));
            $brandIds = Brand::whereIn('slug', array_map('trim', $brandSlugs))->pluck('id');
            $query->whereIn('products.brand_id', $brandIds);
        }

        // Get the filtered or all sizes
        $data = $query->orderBy('sizes.size_name')
            ->distinct()
            ->get();

        return apiResponse('success', 200, $data);
    }


    public function allType()
    {
//        $data = Type::select('id', 'type_name', 'slug')->orderBy('type_name')->get();
//        return apiResponse('success', 200, $data);
        $query = Type::select('types.id', 'types.type_name', 'types.slug')
            ->join('product_types', 'types.id', '=', 'product_types.type_id')
            ->join('products', 'product_types.product_id', '=', 'products.id');

        // Apply category filter if present
        if (request()->has('category')) {
            $categorySlugs = explode(',', request()->get('category'));
            $categoryIds = Category::whereIn('slug', array_map('trim', $categorySlugs))->pluck('id');
            $query->whereIn('products.category_id', $categoryIds);
        }

        // Apply subcategory filter if present
        if (request()->has('subcategory')) {
            $subcategorySlugs = explode(',', request()->get('subcategory'));
            $subcategoryIds = SubCategory::whereIn('slug', array_map('trim', $subcategorySlugs))->pluck('id');
            $query->whereIn('products.subcategory_id', $subcategoryIds);
        }

        // Apply brand filter if present
        if (request()->has('brand')) {
            $brandSlugs = explode(',', request()->get('brand'));
            $brandIds = Brand::whereIn('slug', array_map('trim', $brandSlugs))->pluck('id');
            $query->whereIn('products.brand_id', $brandIds);
        }

        // Get the filtered or all types
        $data = $query->orderBy('types.type_name')
            ->distinct()
            ->get();

        return apiResponse('success', 200, $data);
    }

    public function allHeroBanner()
    {
        $sliders = HeroBanner::where('active_status', 1)->where('banner_type', 'sliders')->select('id', 'title', 'link', 'image', 'banner_type', 'order_no', 'active_status')->orderBy('order_no')->get();
        $banner_top_right = HeroBanner::where('active_status', 1)->where('banner_type', 'banner_top_right')->select('id', 'title', 'link', 'image', 'banner_type', 'order_no', 'active_status')->orderBy('order_no')->get();
        $banner_page_middle = HeroBanner::where('active_status', 1)->where('banner_type', 'banner_page_middle')->select('id', 'title', 'link', 'image', 'banner_type', 'order_no', 'active_status')->orderBy('order_no')->get();
        $banner_promo = HeroBanner::where('active_status', 1)->where('banner_type', 'banner_promo')->select('id', 'title', 'link', 'image', 'banner_type', 'order_no', 'active_status')->orderBy('order_no')->get();
        return response()->json([
            'sliders' => $sliders,
            'banner_top_right' => $banner_top_right,
            'banner_page_middle' => $banner_page_middle,
            'banner_promo' => $banner_promo
        ]);
    }

    public function brandById($id): JsonResponse
    {
        $business = Brand::select('id', 'name', 'slug', 'thumbnail', 'original')->find($id);
        return apiResponse('success', 200, $business);
    }

    public function sendPublicMessage($requestData)
    {
        $mail_data = [
            'activity_type' => 'public_contact_message_to_admin',
            'view_file' => 'mail.layouts.app',
            'to_email' => 'info@tellecto.se',
            'to_name' => "Admin",
            'subject' => $requestData->name . '(' . $requestData->phone . ') wants to contact with you.',
            'message' => $requestData->message,
            'name' => $requestData->name ?? '',
            'email' => $requestData->email ?? '',
            'phone' => $requestData->phone ?? ''
        ];

        // Send the email with the reset link
        sendMail($mail_data);

        return response()->json([
            'status' => 'success',
            'message' => 'Your message has been sent successfully!',
        ]);
    }
}
