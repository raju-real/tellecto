<?php

namespace App\Services\Common;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\Size;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

/**
 * Class ProductService.
 */
class ProductService
{
    public function getActiveProductsOld()
    {
        $data = Product::query();
        $data->active();
        //return Auth::user();
        if (Auth::guard('agent')->check() && authAgentInfo()['user_type'] === "Agent") {
            $business_id = authAgentInfo()['business_id'];
            $data->whereIn('id', function ($query) use ($business_id) {
                $query->select('product_id')
                    ->where('business_id', $business_id)
                    ->from('business_product_prices');
            });

            $data->with([
                'price_info' => function ($price) use ($business_id) {
                    $price->where("business_id", $business_id);
                    $price->select('business_id', 'product_id', 'sale_price');
                }
            ]);
        }


        if (request()->has('product_number')) {
            $data->where('product_number', request()->get('product_number'));
        }
        if (request()->has('ean_number')) {
            $data->where('ean_number', request()->get('ean_number'));
        }

        if (request()->has('model_name')) {
            $data->where('model_name', request()->get('model_name'));
        }
        if (request()->has('stock_status')) {
            $data->where('stock_status', request()->get('stock_status'));
        }

        if (request()->has('is_new_arrival')) {
            return request()->get('is_new_arrival');
            $data->where('is_new_arrival', request()->get('is_new_arrival'));
        }

        if (request()->has('is_best_selling')) {
            $data->where('is_best_selling', request()->get('is_best_selling'));
        }

        if (request()->has('search')) {
            $search = request()->get('search');
            $data->where(function ($query) use ($search) {
                $query->where('product_name', "LIKE", "%{$search}%")
                    ->orWhere('product_number', "LIKE", $search)
                    ->orWhere('ean_number', $search)
                    ->orWhere('model_name', $search);
            });
        }

        if (request()->has('category')) {
            $slugs = explode(',', request()->get('category'));
            $category_ids = [];
            foreach ($slugs as $slug) {
                $category = Category::whereSlug($slug)->first();
                if (isset($category)) {
                    array_push($category_ids, $category->id);
                }
            }
            $data->whereIn('category_id', $category_ids);
        }

        if (request()->has('subcategory')) {
            $slugs = explode(', ', request()->get('subcategory'));
            $sub_category_ids = [];
            foreach ($slugs as $slug) {
                $subcategory = SubCategory::whereSlug($slug)->first();
                if (isset($subcategory)) {
                    array_push($sub_category_ids, $subcategory->id);
                }
            }
            $data->whereIn('subcategory_id', $sub_category_ids);
        }

        if (request()->has('brand')) {
            $slugs = explode(', ', request()->get('brand'));
            $brand_ids = [];
            foreach ($slugs as $slug) {
                $brand = Brand::whereSlug($slug)->first();
                if (isset($brand)) {
                    array_push($brand_ids, $brand->id);
                }
            }
            $data->whereIn('brand_id', $brand_ids);
        }

        if (request()->has('sizes')) {
            $slugs = array_map('trim', explode(',', request()->get('sizes')));
            $size_ids = Size::whereIn('slug', $slugs)->pluck('id');
            if ($size_ids->isNotEmpty()) {
                $product_ids = ProductSize::whereIn('size_id', $size_ids)->pluck('product_id');
                $data->whereIn('id', $product_ids);
            }
        }

        if (request()->has('colors')) {
            $slugs = array_map('trim', explode(',', request()->get('colors')));
            $color_ids = Color::whereIn('slug', $slugs)->pluck('id');
            if ($color_ids->isNotEmpty()) {
                $product_ids = ProductColor::whereIn('color_id', $color_ids)->pluck('product_id');
                $data->whereIn('id', $product_ids);
            }
        }


        if (request()->has('filtered_as')) {
            $filtered_as = request()->get('filtered_as');
            if ($filtered_as == 'NewArrivals') {
                $data->orderBy('created_at', 'desc');
            } elseif ($filtered_as === "bestSelling") {
                $data->whereIn('id', function ($query) {
                    $query->select('product_id')
                        ->from('order_items')->latest();
                });
            }
        }

        $data->with([
            'category:id,name,slug,thumbnail,original,vat_type',
            'subcategory:id,name,slug,thumbnail,original',
            'brand:id,name,slug,thumbnail,original',
            'colors:id,product_id,color_id',
            'sizes:id,product_id,size_id',
            'types:id,product_id,type_id',
            'images:id,product_id,image,is_thumbnail',
        ]);

        $data->publicSelectedFields();

        return paginationResponse('success', 200, $data, request('showPerPage'));
    }

    public function getActiveProducts()
    {
        $data = Product::query()->publicVisible();

        if (Auth::guard('agent')->check() && authAgentInfo()['user_type'] === "Agent") {
            $business_id = authAgentInfo()['business_id'];
            $data->whereIn('id', function ($query) use ($business_id) {
                $query->select('product_id')
                    ->from('business_product_prices')
                    ->where('business_id', $business_id);
            });

            $data->with([
                'price_info' => function ($price) use ($business_id) {
                    $price->select('business_id', 'product_id', 'sale_price')
                        ->where('business_id', $business_id);
                }
            ]);
        }

        if ($product_number = request()->get('product_number')) {
            $data->where('product_number', $product_number);
        }

        if ($ean_number = request()->get('ean_number')) {
            $data->where('ean_number', $ean_number);
        }

        if ($model_name = request()->get('model_name')) {
            $data->where('model_name', $model_name);
        }

        if ($stock_status = request()->get('stock_status')) {
            $data->where('stock_status', $stock_status);
        }

        if (request()->has('is_new_arrival')) {
            $data->where('is_new_arrival', request()->get('is_new_arrival'));
        }

        if (request()->has('is_best_selling')) {
            $data->where('is_best_selling', request()->get('is_best_selling'));
        }

        if ($search = request()->get('search')) {
            $data->where(function ($query) use ($search) {
                $query->where('product_name', 'LIKE', "{$search}%")
                    ->orWhere('product_number', 'LIKE', $search)
                    ->orWhere('ean_number', $search)
                    ->orWhere('model_name', $search);
            });
        }

        if ($category_slugs = request()->get('category')) {
            $category_ids = Category::whereIn('slug', explode(',', $category_slugs))->pluck('id');
            $data->whereIn('category_id', $category_ids);
        }

        if ($subcategory_slugs = request()->get('subcategory')) {
            $subcategory_ids = SubCategory::whereIn('slug', array_map('trim', explode(',', $subcategory_slugs)))->pluck('id');
            $data->whereIn('subcategory_id', $subcategory_ids);
        }

        if ($brand_slugs = request()->get('brand')) {
            $brand_ids = Brand::whereIn('slug', array_map('trim', explode(',', $brand_slugs)))->pluck('id');
            $data->whereIn('brand_id', $brand_ids);
        }

        if ($size_slugs = request()->get('sizes')) {
            $size_ids = Size::whereIn('slug', array_map('trim', explode(',', $size_slugs)))->pluck('id');
            if ($size_ids->isNotEmpty()) {
                $data->whereIn('id', function ($query) use ($size_ids) {
                    $query->select('product_id')->from('product_sizes')->whereIn('size_id', $size_ids);
                });
            }
        }

        if ($color_slugs = request()->get('colors')) {
            $color_ids = Color::whereIn('slug', array_map('trim', explode(',', $color_slugs)))->pluck('id');
            if ($color_ids->isNotEmpty()) {
                $data->whereIn('id', function ($query) use ($color_ids) {
                    $query->select('product_id')->from('product_colors')->whereIn('color_id', $color_ids);
                });
            }
        }


        if ($filtered_as = request()->get('filtered_as')) {
            switch ($filtered_as) {
                case 'newArrivals':
                    //$data->orderBy('created_at', 'desc');
                    $data->where('is_new_arrival',true);
                    break;
                case 'bestSelling':
                    $data->where('is_best_selling',true);
//                    $data->whereIn('id', function ($query) {
//                        $query->select('product_id')->from('order_items')->latest();
//                    });
                    break;
            }
        }


        // Short By Price
//        if ($short_by = request()->get('short_by')) {
//            $data->join('business_product_prices as price_info', 'products.id', '=', 'price_info.product_id')
//                ->orderBy('price_info.sale_price', $short_by === 'lowest' ? 'asc' : 'desc');
//        }

//        $data->with([
//            'category:id,name,slug,thumbnail,original,vat_type',
//            'subcategory:id,name,slug,thumbnail,original',
//            'brand:id,name,slug,thumbnail,original',
//            'colors:id,product_id,color_id',
//            'sizes:id,product_id,size_id',
//            'types:id,product_id,type_id',
//            'images:id,product_id,image,is_thumbnail',
//        ]);
        $data->select('id','product_name','slug','inventory','ean_number','stock_status','active_status','product_type');

        return paginationResponse('success', 200, $data, request('showPerPage'));
    }

    public function productDetails($slug)
    {
        $data = Product::query();
        $data->where('slug', $slug);
        $data->publicVisible();
        // return authAgentInfo();
        if (auth()->guard('agent')->check() && !empty(authAgentInfo()) && authAgentInfo()['user_type'] === "Agent") {
            $business_id = authAgentInfo()['business_id'];
            $data->with([
                'price_info' => function ($price) use ($business_id) {
                    $price->where("business_id", $business_id)
                        ->select('business_id', 'product_id', 'sale_price');
                }
            ]);
        }

        $data->with([
            'category:id,name,slug,thumbnail,original,vat_type',
            'subcategory:id,name,slug,thumbnail,original',
            'brand:id,name,slug,thumbnail,original',
            'colors:id,product_id,color_id',
            'sizes:id,product_id,size_id',
            'types:id,product_id,type_id',
            'images:id,product_id,image,is_thumbnail'
        ]);

        $result = $data->publicSelectedFields()->first();

        if (!$result) {
            return response()->json([
                'status' => 'error',
                'message' => 'No data found'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $result
        ]);
    }

    public function productByBrands($slug)
    {
        $brand = Brand::whereSlug($slug)->select('id', 'name', 'slug', 'thumbnail', 'original', 'active_status')->first();
        $data = Product::query();
        $data->where('brand_id', $brand->id);
        $data->active();

        if (!empty(authAgentInfo()) && authAgentInfo()['user_type'] === "Agent") {
            $business_id = authAgentInfo()['business_id'];
            $data->whereIn('id', function ($query) use ($business_id) {
                $query->select('product_id')
                    ->where('business_id', $business_id)
                    ->from('business_product_prices');
            });
            $data->with([
                'price_info' => function ($price) use ($business_id) {
                    $price->where("business_id", $business_id);
                    $price->select('business_id', 'product_id', 'sale_price');
                }
            ]);
        }

        $data->with([
            'category:id,name,slug,thumbnail,original',
            'subcategory:id,name,slug,thumbnail,original',
            'brand:id,name,slug,thumbnail,original',
            'images:id,product_id,image,is_thumbnail'
        ]);

        return brandProductResponse('success', $brand, $data);
    }

    public function brandBySlug($slug)
    {
        $brand = Brand::select('id', 'name', 'slug', 'thumbnail', 'original')->where('slug', $slug)->first();
        return response()->json([
            'status' => 'success',
            'data' => $brand
        ]);
    }
}

