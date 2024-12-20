<?php

namespace App\Services\Business;

use App\Jobs\SetBusinessProduct;
use App\Jobs\UpdateBusinessProductPrice;
use App\Models\BusinessProductPrice;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class ProductService.
 */
class ProductService
{
    public $loopCounter = 0;

    public function productListOld(): JsonResponse
    {
        $businessId = auth()->user()->id;

        $data = BusinessProductPrice::with([
            'product' => function ($product) {
                // Apply filters based on query parameters
                $product->when(request()->get('category_id'), function ($query) {
                    $query->where('category_id', request()->get('category_id'));
                });

                if ($product_number = request()->get('product_number')) {
                    $product->where('product_number', $product_number);
                }

                if ($ean_number = request()->get('ean_number')) {
                    $product->where('ean_number', $ean_number);
                }

                if ($model_name = request()->get('model_name')) {
                    $product->where('model_name', $model_name);
                }

                if (request()->has('search')) {
                    $search = request()->get('search');
                    $product->where(function ($query) use ($search) {
                        $query->where('product_name', "LIKE", "%{$search}%")
                            ->orWhere('product_number', $search)
                            ->orWhere('ean_number', $search)
                            ->orWhere('model_name', $search);
                    });
                }

                $product->with([
                    'category' => function ($query) {
                        $query->select('id', 'name', 'slug');
                    },
                    'subcategory' => function ($query) {
                        $query->select('id', 'name', 'slug');
                    },
                    'brand' => function ($query) {
                        $query->select('id', 'name', 'slug');
                    },
                    'images' => function ($query) {
                        $query->select('id', 'product_id', 'image');
                    }
                ]);
                $product->selectedFieldsForBusiness();
            }
        ])->where('business_id', $businessId)
            ->select('id', 'product_id', 'business_id', 'previous_price', 'price', 'profit_type', 'profit', 'sale_price', 'profit_amount');

        $products = $data;
        return paginationResponse('success', 200, $products, request('showPerPage'));
    }

    public function productList()
    {
        $data = Product::query();
        $data->publicVisible();
        $business_id = Auth::id();
        $data->whereIn('id', function ($query) use ($business_id) {
            $query->select('product_id')
                ->from('business_product_prices')
                ->where('business_id', $business_id);
        });

        $data->with([
            'business_price' => function ($price) use ($business_id) {
                $price->select('id', 'product_id', 'business_id', 'previous_price', 'price', 'profit_type', 'profit', 'sale_price', 'profit_amount')
                    ->where('business_id', $business_id);
            }
        ]);

        if ($product_name = request()->get('product_name')) {
            $data->where('product_name', 'LIKE', "{$product_name}%");
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

        $data->when(request()->get('category_id'), function ($query) {
            $query->where('category_id', request()->get('category_id'));
        });
        $data->when(request()->get('subcategory_id'), function ($query) {
            $query->where('subcategory_id', request()->get('subcategory_id'));
        });
        $data->when(request()->get('brand_id'), function ($query) {
            $query->where('brand_id', request()->get('brand_id'));
        });

        if ($search = request()->get('search')) {
            $data->where(function ($query) use ($search) {
                $query->where('product_name', 'LIKE', "{$search}%")
                    ->orWhere('product_number', 'LIKE', $search)
                    ->orWhere('ean_number', $search)
                    ->orWhere('model_name', $search);
            });
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

        $data->publicSelectedFields()->with('thumbnail');

        return paginationResponse('success', 200, $data, request('showPerPage'));
    }


    public function fetchProducts(): JsonResponse
    {
        $businessId = auth()->user()->id;
        SetBusinessProduct::dispatch($businessId);

//        $command = 'php ' . base_path('artisan') . ' business:set-product > /dev/null 2>&1 &';
//        exec($command);
        return response()->json([
            'status' => 'success',
            'message' => 'Product fetching process started successfully. You will get an notification after complete.'
        ]);
    }

    public function updateProduct(): JsonResponse
    {
        $businessId = auth()->user()->id;
        UpdateBusinessProductPrice::dispatch($businessId);

        return response()->json([
            'status' => 'success',
            'message' => 'Product updating process started successfully. You will get an notification after complete.'
        ]);
    }

    public function setGlobalProductProfit($requestData)
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0); // Remove the execution time limit

        DB::table('business_product_prices')->where('business_id', Auth::id())->update(['profit_type' => $requestData->profit_type, 'profit' => $requestData->profit]);
        // Update products table
        DB::table('business_product_prices as bpp')
            ->where('business_id', Auth::id())
            ->update([
                'bpp.price' => DB::raw('ROUND(p.sale_price)'),
                'bpp.sale_price' => DB::raw("
                        ROUND(
                            CASE
                                WHEN bpp.profit_type = 'PERCENTAGE' THEN p.sale_price + (bpp.sale_price * bpp.profit / 100)
                                WHEN bpp.profit_type = 'FLAT' THEN bpp.sale_price + bpp.profit
                                ELSE bpp.price
                            END
                        )
                    "),
                'bpp.profit_amount' => DB::raw("
                        ROUND(
                            CASE
                                WHEN bpp.profit_type = 'PERCENTAGE' THEN (bpp.sale_price * bpp.profit / 100)
                                WHEN bpp.profit_type = 'FLAT' THEN bpp.profit
                                ELSE 0
                            END
                        )
                    "),
                'bpp.updated_at' => DB::raw('NOW()')
            ]);

//        BusinessProductPrice::where('business_id', auth()->user()->id)->chunk(1000, function ($products) use ($requestData) {
//            foreach ($products as $product) {
//                $price = $product->price;
//                $calculatedValues = Product::calculateProfit($price, $requestData->profit_type, $requestData->profit);
//
//                $product->profit_type = $requestData->profit_type;
//                $product->profit = $requestData->profit;
//                $product->profit_amount = $calculatedValues['profit_amount'];
//                $product->sale_price = $calculatedValues['sale_price'];
//                $product->updated_at = now();
//                $product->save();
//            }
//        });
        return response()->json([
            'status' => 'success',
            'message' => 'Product updated successfully'
        ]);
    }

    public function setProductWiseProfit($requestData)
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0); // Remove the execution time limit
        foreach ($requestData->toArray() as $key => $data) {
            $id = $data['id'];
            $profit_type = $data['profit_type'];
            $profit = $data['profit'];

            //$product = BusinessProductPrice::where('business_id', auth()->user()->id)->where('product_id', $id)->first();
            $product = BusinessProductPrice::find($id);
            $price = $product->price;
            $calculatedValues = Product::calculateProfit($price, $profit_type, $profit);
            // Update product data
            $product->profit_type = $profit_type;
            $product->profit = $profit;
            $product->profit_amount = $calculatedValues['profit_amount'];
            $product->sale_price = $calculatedValues['sale_price'];
            $product->updated_at = now();
            $product->save();
            $this->loopCounter++;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Total ' . $this->loopCounter . ' Products updated successfully'
        ]);
    }

    public function setSingleProductProfit($requestData)
    {
        $product = BusinessProductPrice::where('business_id', auth()->user()->id)->where('product_id', $requestData->id)->first();

//        return $product;
        $price = $product->price;
        $calculatedValues = Product::calculateProfit($price, $requestData->profit_type, $requestData->profit);

        $product->profit_type = $requestData->profit_type;
        $product->profit = $requestData->profit;
        $product->profit_amount = $calculatedValues['profit_amount'];
        $product->sale_price = $calculatedValues['sale_price'];
        $product->updated_at = now();
        if ($product->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Product profit has been updated successfully!'
            ]);
        } else {
            return failedResponse();
        }
    }

    public function showProductByIdForBusiness($id)
    {
        $businessId = auth()->user()->id;

        $data = BusinessProductPrice::with([
            'product' => function ($product) {
                $product->when(request()->get('category_id'), function ($query) {
                    $query->where('category_id', request()->get('category_id'));
                });
                $product->with([
                    'category' => function ($query) {
                        $query->select('id', 'name', 'slug');
                    },
                    'subcategory' => function ($query) {
                        $query->select('id', 'name', 'slug');
                    },
                    'brand' => function ($query) {
                        $query->select('id', 'name', 'slug');
                    },
                    'colors' => function ($query) {
                        $query->select('id', 'product_id', 'color_id');
                    },
                    'sizes' => function ($query) {
                        $query->select('id', 'product_id', 'size_id');
                    },
                    'images' => function ($query) {
                        $query->select('id', 'product_id', 'image');
                    }
                ]);
                $product->selectedFields();
            }
        ])->where('business_id', $businessId)->where('product_id', $id)
            ->select('id', 'product_id', 'business_id', 'previous_price', 'price', 'profit_type', 'profit', 'sale_price', 'profit_amount');
        $b_product = $data->first();
        return response()->json([
            'data' => $b_product,
            'status' => 'success',
            'message' => ''
        ]);
    }
}
