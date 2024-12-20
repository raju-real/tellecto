<?php

namespace App\Services\Admin;

use App\Exports\ProductsExport;
use App\Jobs\BulkProductUpdate;
use App\Jobs\FetchProduct;
use App\Jobs\SaveProduct;
use App\Jobs\SetBulkProductProfit;
use App\Jobs\UpdateProducts;
use App\Models\BusinessProductPrice;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductImage;
use App\Models\ProductSize;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Opcodes\LogViewer\Logs\Log;

/**
 * Class ProductService.
 */
class ProductService
{
    protected $loopCounter = 0;

    public function productList()
    {
        $data = Product::query();
        $data->when(request()->get('product_number'), function ($query) {
            $query->where('product_number', request()->get('product_number'));
        });
        $data->when(request()->get('ean_number'), function ($query) {
            $query->where('ean_number', request()->get('ean_number'));
        });
        $data->when(request()->get('model_name'), function ($query) {
            $query->where('model_name', request()->get('model_name'));
        });

//        $data->when(request()->get('search'), function ($query) {
//            $search = request()->get('search');
//            $query->whereAny(['product_name'], "LIKE", "%{$search}%");
//        });

        $data->when(request()->get('search'), function ($query) {
            $search = request()->get('search');
            $query->where(function ($query) use ($search) {
                $query->where('product_name', "LIKE", "%{$search}%")
                    ->orWhere('product_number', $search)
                    ->orWhere('ean_number', $search)
                    ->orWhere('model_name', $search);
            });
        });

        $data->when(request()->get('category_id'), function ($query) {
            $query->where('category_id', request()->get('category_id'));
        });
        $data->when(request()->get('subcategory_id'), function ($query) {
            $query->where('subcategory_id', request()->get('subcategory_id'));
        });
        $data->when(request()->get('brand_id'), function ($query) {
            $query->where('brand_id', request()->get('brand_id'));
        });
        if (request()->has('active_status')) {
            $data->where('active_status', request()->get('active_status'));
        }
        if (request()->has('source_active_status')) {
            $data->where('source_active_status', request()->get('source_active_status'));
        }

        if ($stock_status = request()->get('stock_status')) {
            $data->where('stock_status', $stock_status);
        }
        $data->when(request()->get('profit_type'), function ($query) {
            $query->where('profit_type', request()->get('profit_type'));
        });
        $data->with([
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
                $query->select('id', 'product_id', 'image', 'is_thumbnail');
            }
        ]);
        $data->selectedFields()->with('thumbnail');
        $products = $data;
        return paginationResponse('success', 200, $products, request('showPerPage'));
    }

    public function getActiveProducts()
    {
        $data = Product::query();
        $data->active()->where('source_active_status', 1);
        $data->when(request()->get('product_number'), function ($query) {
            $query->where('product_number', request()->get('product_number'));
        });
        $data->when(request()->get('ean_number'), function ($query) {
            $query->where('ean_number', request()->get('ean_number'));
        });
        $data->when(request()->get('model_name'), function ($query) {
            $query->where('model_name', request()->get('model_name'));
        });

        $data->when(request()->get('search'), function ($query) {
            $search = request()->get('search');
            $query->where(function ($query) use ($search) {
                $query->where('product_name', "LIKE", "%{$search}%")
                    ->orWhere('product_number', $search)
                    ->orWhere('ean_number', $search)
                    ->orWhere('model_name', $search);
            });
        });

        $data->when(request()->get('category_id'), function ($query) {
            $query->where('category_id', request()->get('category_id'));
        });
        $data->when(request()->get('subcategory_id'), function ($query) {
            $query->where('subcategory_id', request()->get('subcategory_id'));
        });
        $data->when(request()->get('brand_id'), function ($query) {
            $query->where('brand_id', request()->get('brand_id'));
        });
        $data->when(request()->get('active_status'), function ($query) {
            $query->where('active_status', request()->get('active_status'));
        });
        if ($stock_status = request()->get('stock_status')) {
            $data->where('stock_status', $stock_status);
        }
        $data->when(request()->get('profit_type'), function ($query) {
            $query->where('profit_type', request()->get('profit_type'));
        });
        $data->with([
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
                $query->select('id', 'product_id', 'image', 'is_thumbnail');
            }
        ]);
        $data->selectedFields();
        $products = $data;
        return paginationResponse('success', 200, $products, request('showPerPage'));
    }

    public function fetchProducts(): void
    {
        FetchProduct::dispatch();
    }

    public function saveProducts(): JsonResponse
    {
        SaveProduct::dispatch();
        return response()->json([
            'status' => 'success',
            'message' => 'Product fetching process started successfully. You will get an notification after complete.'
        ]);
    }

    public function updateProducts($apiUrl): JsonResponse
    {
        try {
            updateProducts::dispatch($apiUrl);
            return response()->json([
                'status' => 'success',
                'message' => 'Product updating process started successfully. You will get an notification after complete.'
            ]);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function productById($id): JsonResponse
    {
        $product = Product::with([
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
                $query->select('id', 'product_id', 'image', 'is_thumbnail');
            }
        ])->selectedFields()->findOrFail($id);
        return apiResponse('success', 200, $product);
    }

    public function productBySlug($slug): JsonResponse
    {
        $product = Product::with([
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
                $query->select('id', 'product_id', 'image', 'is_thumbnail');
            }
        ])->where('slug', $slug)->selectedFields()->first();
        return apiResponse('success', 200, $product);
    }

    public function removeProductImages($requestData)
    {
        if (!empty($requestData->image_ids)) {

            foreach ($requestData->image_ids as $id) {
                if (ProductImage::where('id', $id)->exists()) {
                    $product_image = ProductImage::find($id);
                    if (!empty($product_image->image) and file_exists($product_image->image)) {
                        unlink($product_image->image);
                    }
                    $product_image->delete();
                    $this->loopCounter++;
                }
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Total ' . $this->loopCounter . ' image deleted successfully'
            ]);
        } else {
            return failedResponse();
        }
    }

    public function productByNumber($product_number): JsonResponse
    {
        $product = Product::where('product_number', $product_number)->firstOrFail();
        return apiResponse('success', 200, $product);
    }

    public function setBulkProfit($data)
    {
        return SetBulkProductProfit::dispatch($data);
    }

    public function setManualProfit($data)
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0); // Remove the execution time limit

        $product_numbers = $data->product_numbers;
        $profit_types = $data->profit_types;
        $profits = $data->profits;
        //dd(count($product_numbers),count($profit_types),count($profits));

        // Check if all arrays have the same length
        if (count($product_numbers) !== count($profit_types) || count($product_numbers) !== count($profits)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Execution failed. Ensure product numbers, profit types, and profits arrays have the same length.'
            ]);
        }

        if (count($product_numbers)) {
            foreach ($product_numbers as $key => $product_number) {
                $product = Product::where('product_number', $product_number)->first();
                $price = $product->price;
                $calculatedValues = Product::calculateProfit($price, $profit_types[$key], $profits[$key]);
                // Update product data
                $product->profit_type = $profit_types[$key];
                $product->profit = $profits[$key];
                $product->profit_amount = $calculatedValues['profit_amount'];
                $product->sale_price = $calculatedValues['sale_price'];
                $product->updated_at = now();
                $product->save();
                BusinessProductPrice::where('product_id', $product->id)
                    ->join('products', 'business_product_prices.product_number', '=', 'products.product_number')
                    ->update([
                        'price' => DB::raw('ROUND(products.sale_price)'),
                        'sale_price' => DB::raw('ROUND(CASE WHEN profit_type = "PERCENTAGE" THEN (products.sale_price * profit / 100) WHEN profit_type = "FLAT" THEN products.sale_price + profit ELSE products.sale_price END)'),
                        'previous_price' => DB::raw('ROUND(CASE WHEN price != products.sale_price THEN price ELSE previous_price END)'),
                        'profit_amount' => DB::raw('ROUND(CASE WHEN profit_type = "PERCENTAGE" THEN (products.sale_price * profit / 100) WHEN profit_type = "FLAT" THEN profit ELSE 0 END)'),
                        'updated_at' => now(),
                        'last_updated_at' => now(),
                    ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Total ' . count($product_numbers) . ' Products updated successfully'
        ]);
    }

    public function setBulkStatus($data)
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0); // Remove the execution time limit

        if (Product::query()->update(['active_status' => $data->active_status])) {
            return response()->json([
                'status' => 'success',
                'message' => 'Product status has been updated successfully!'
            ]);
        } else {
            return failedResponse();
        }
    }

    public function setManualStatus($data)
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0); // Remove the execution time limit

        $product_numbers = $data->product_numbers;
        $active_status = $data->active_status;

        // Check if all arrays have the same length
        if (count($product_numbers) !== count($active_status)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Execution failed. Ensure product numbers and status arrays have the same length.'
            ]);
        }

        if (count($product_numbers)) {
            foreach ($product_numbers as $key => $product_number) {
                $product = Product::where('product_number', $product_number)->first();
                $product->active_status = $active_status[$key];
                $product->updated_at = now();
                $product->save();
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Total ' . count($product_numbers) . ' Products updated successfully'
        ]);
    }

    public function setBulkCategory($data)
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0); // Remove the execution time limit

        if (Product::query()->update(
            [
                'category_id' => $data->category_id,
                'subcategory_id' => $data->subcategory_id
            ]
        )) {
            return response()->json([
                'status' => 'success',
                'message' => 'Product category & subcategory has been updated successfully!'
            ]);
        } else {
            return failedResponse();
        }
    }

    public function setManualCategory($data)
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0); // Remove the execution time limit

        $product_numbers = $data->product_numbers;
        $categories = $data->categories;
        $subcategories = $data->subcategories;
        // Check if all arrays have the same length
        if (count($product_numbers) !== count($categories) || count($product_numbers) !== count($subcategories)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Execution failed. Ensure product numbers, categories, and subcategories arrays have the same length.'
            ]);
        }

        if (count($product_numbers)) {
            foreach ($product_numbers as $key => $product_number) {
                $product = Product::where('product_number', $product_number)->first();
                $product->category_id = $categories[$key];
                $product->subcategory_id = $subcategories[$key];
                $product->updated_at = now();
                $product->save();
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Total ' . count($product_numbers) . ' Products updated successfully'
        ]);
    }

    public function setGlobalProductProfit($requestData)
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0); // Remove the execution time limit

        DB::table('products')->update(['profit_type' => $requestData->profit_type, 'profit' => $requestData->profit]);
        try {
            DB::beginTransaction();
            // Update the `products` table
            DB::statement('UPDATE products
                SET
                    profit_amount = ROUND(
                        CASE
                            WHEN profit_type = "FLAT" THEN profit
                            WHEN profit_type = "PERCENTAGE" THEN (price * profit) / 100
                            ELSE 0
                        END
                    ),
                    sale_price = ROUND(
                        CASE
                            WHEN profit_type = "FLAT" THEN price + profit
                            WHEN profit_type = "PERCENTAGE" THEN price + ((price * profit) / 100)
                            ELSE price
                        END
                    ),
                    updated_at = NOW();
                ');
            // Commit the first transaction to ensure changes are persisted
            DB::commit();
            // Begin a new transaction for the second update
            DB::beginTransaction();
            // Update the `business_product_prices` table
            DB::statement('
                        UPDATE business_product_prices bpp
                        JOIN products p ON bpp.product_number = p.product_number
                        SET
                            bpp.price = ROUND(p.sale_price),
                            bpp.sale_price = ROUND(
                                CASE
                                    WHEN bpp.profit_type = "PERCENTAGE" THEN p.sale_price + (p.sale_price * bpp.profit / 100)
                                    WHEN bpp.profit_type = "FLAT" THEN p.sale_price + bpp.profit
                                    ELSE p.sale_price
                                END
                            ),
                            bpp.previous_price = ROUND(
                                CASE
                                    WHEN bpp.price != p.sale_price THEN bpp.price
                                    ELSE bpp.previous_price
                                END
                            ),
                            bpp.profit_amount = ROUND(
                                CASE
                                    WHEN bpp.profit_type = "PERCENTAGE" THEN (p.sale_price * bpp.profit / 100)
                                    WHEN bpp.profit_type = "FLAT" THEN bpp.profit
                                    ELSE 0
                                END
                            ),
                            bpp.updated_at = NOW(),
                            bpp.last_updated_at = NOW()
                    ');
            DB::commit(); // Commit the second transaction
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in updating product data: ' . $e->getMessage());
            throw $e;
        }

        //SetBulkProductProfit::dispatch($requestData);
        return response()->json([
            'status' => 'success',
            'message' => 'Information has been updated successfully.'
        ]);
//        Product::chunk(1000, function ($products) use ($requestData) {
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
//                $this->loopCounter++;
//            }
//        });
//        return response()->json([
//            'status' => 'success',
//            'message' => 'Total ' . $this->loopCounter . ' Product updated successfully'
//        ]);
    }

    public function setProductWiseProfit($requestData)
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0); // Remove the execution time limit
        foreach ($requestData->toArray() as $key => $data) {
            $id = $data['id'];
            $profit_type = $data['profit_type'];
            $profit = $data['profit'];

            $product = Product::where('id', $id)->first();
            $price = $product->price;

            $calculatedValues = Product::calculateProfit($price, $profit_type, $profit);
            // Update product data
            $product->profit_type = $profit_type;
            $product->profit = $profit;
            $product->profit_amount = $calculatedValues['profit_amount'];
            $product->sale_price = $calculatedValues['sale_price'];
            $product->updated_at = now();
            $product->save();
            // Update business products table
            DB::table('business_product_prices as bpp')
                ->where('bpp.product_id', $id)
                ->join('products as p', 'bpp.product_number', '=', 'p.product_number')
                ->update([
                    'bpp.price' => DB::raw('ROUND(p.sale_price)'),
                    'bpp.sale_price' => DB::raw("
                        ROUND(
                            CASE
                                WHEN bpp.profit_type = 'PERCENTAGE' THEN p.sale_price + (p.sale_price * bpp.profit / 100)
                                WHEN bpp.profit_type = 'FLAT' THEN p.sale_price + bpp.profit
                                ELSE p.sale_price
                            END
                        )
                    "),
                    'bpp.previous_price' => DB::raw("
                        ROUND(
                            CASE
                                WHEN bpp.price != p.sale_price THEN bpp.price
                                ELSE bpp.previous_price
                            END
                        )
                    "),
                    'bpp.profit_amount' => DB::raw("
                        ROUND(
                            CASE
                                WHEN bpp.profit_type = 'PERCENTAGE' THEN (p.sale_price * bpp.profit / 100)
                                WHEN bpp.profit_type = 'FLAT' THEN bpp.profit
                                ELSE 0
                            END
                        )
                    "),
                    'bpp.updated_at' => DB::raw('NOW()')
                ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Products updated successfully'
        ]);
    }

    public function setSingleProductProfit($requestData)
    {
        $id = $requestData->id;
        $product = Product::where('id', $id)->first();
        $price = $product->price;
        $calculatedValues = Product::calculateProfit($price, $requestData->profit_type, $requestData->profit);

        $product->profit_type = $requestData->profit_type;
        $product->profit = $requestData->profit;
        $product->profit_amount = $calculatedValues['profit_amount'];
        $product->tellecto_last_price = $product->sale_price;
        $product->sale_price = $calculatedValues['sale_price'];
        $product->updated_at = now();

        if ($product->save()) {
            // Update business products table
            DB::table('business_product_prices as bpp')
                ->where('bpp.product_id', $id)
                ->join('products as p', 'bpp.product_number', '=', 'p.product_number')
                ->update([
                    'bpp.price' => DB::raw('ROUND(p.sale_price)'),
                    'bpp.sale_price' => DB::raw("
                        ROUND(
                            CASE
                                WHEN bpp.profit_type = 'PERCENTAGE' THEN p.sale_price + (p.sale_price * bpp.profit / 100)
                                WHEN bpp.profit_type = 'FLAT' THEN p.sale_price + bpp.profit
                                ELSE p.sale_price
                            END
                        )
                    "),
                    'bpp.previous_price' => DB::raw("
                        ROUND(
                            CASE
                                WHEN bpp.price != p.sale_price THEN bpp.price
                                ELSE bpp.previous_price
                            END
                        )
                    "),
                    'bpp.profit_amount' => DB::raw("
                        ROUND(
                            CASE
                                WHEN bpp.profit_type = 'PERCENTAGE' THEN (p.sale_price * bpp.profit / 100)
                                WHEN bpp.profit_type = 'FLAT' THEN bpp.profit
                                ELSE 0
                            END
                        )
                    "),
                    'bpp.updated_at' => DB::raw('NOW()'),
                    'bpp.last_updated_at' => DB::raw('NOW()'),
                ]);
//            $businessProducts = BusinessProductPrice::where('product_id', $id)->get();
//            foreach ($businessProducts as $business) {
//                $bProduct = BusinessProductPrice::find($business->id);
//                $bProduct->price = $calculatedValues['sale_price'];
//                $calculatedBusinessValues = BusinessProductPrice::calculateProfit($calculatedValues['sale_price'], $bProduct->profit_type, $bProduct->profit);
//                $bProduct->previous_price = $bProduct->sale_price;
//                $bProduct->sale_price = $calculatedBusinessValues['sale_price'];
//                $bProduct->save();
//            }
            return response()->json([
                'status' => 'success',
                'message' => 'Profit updated successfully!'
            ]);

        } else {
            return failedResponse();
        }
    }

    public function setProductCategoryGlobal($requestData)
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0); // Remove the execution time limit

        if (Product::query()->update(
            [
                'category_id' => $requestData->category_id,
                'subcategory_id' => $requestData->subcategory_id
            ]
        )) {
            return response()->json([
                'status' => 'success',
                'message' => 'Product category & subcategory has been updated successfully!'
            ]);
        } else {
            return failedResponse();
        }
    }

    public function setProductWiseCategory($requestData)
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0); // Remove the execution time limit

        foreach ($requestData->toArray() as $key => $data) {
            $id = $data['id'];
            Product::where('id', $id)->update([
                'category_id' => $data['category_id'],
                'subcategory_id' => $data['subcategory_id'],
                'updated_at' => now()
            ]);
            $this->loopCounter++;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Total ' . $this->loopCounter . ' Products updated successfully'
        ]);
    }

    public function setSingleProductCategory($requestData)
    {
        Product::where('id', $requestData->id)->update([
            'category_id' => $requestData->category_id,
            'subcategory_id' => $requestData->subcategory_id,
            'updated_at' => now()
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'The product information has been updated successfully!'
        ]);
    }

    public function setProductWiseStatus($requestData)
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0); // Remove the execution time limit
        foreach ($requestData->toArray() as $data) {
            $product = Product::findOrFail($data['id']);
            $product->active_status = $data['active_status'];
            $product->updated_at = now();
            $product->save();

            if ($product->active_status == 1 && $product->source_active_status == 1 && $product->sale_price > 0) {
                $product->searchable();
            } else {
                $product->unsearchable();
            }

//            $product->update([
//                'active_status' => $data['active_status'],
//                'updated_at' => now()
//            ]);
            setBusinessProductPrice($data['id'], $data['active_status']);
            $this->loopCounter++;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Total ' . $this->loopCounter . ' Products updated successfully'
        ]);
    }

    public function setSingleProductStatus($requestData)
    {
//        Product::where('id', $requestData->id)->update([
//            'active_status' => $requestData->active_status,
//            'updated_at' => now()
//        ]);
        $product = Product::find($requestData->id);
        $product->active_status = $requestData->active_status;
        $product->updated_at = now();
        $product->save();

        if ($product->active_status == 1 && $product->source_active_status == 1 && $product->sale_price > 0) {
            $product->searchable();
        } else {
            $product->unsearchable();
        }
        // setBusinessProductPrice($requestData['id'], $requestData['active_status']);
        return response()->json([
            'status' => 'success',
            'message' => 'Product status has been updated successfully'
        ]);
    }

    public function setProductDescription($data)
    {
        Product::where('id', $data->id)->update([
            'product_description' => $data->description,
            'updated_at' => now()
        ]);
        return response()->json([
            'status' => 'success',
            'message' => "The product description has been updated successfully!"
        ]);
    }

    public function setProductImage($requestData)
    {
        $product = Product::where('id', $requestData->id)->firstOrFail();
        if ($requestData->file('thumbnail')) {
            $product->product_image = uploadImage($requestData->file('thumbnail'), 'product');
        }
        $product->save();
        if (isset($requestData->images) && count($requestData->images)) {
            foreach ($requestData->images as $image) {
                $product_image = new ProductImage();
                $product_image->product_id = $product->id;
                $product_image->product_number = $product->product_number;
                $product_image->image = uploadImage($image, 'product');
                $product_image->save();
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => "The product image has been updated successfully!"
        ]);
    }

    public function singleProductUpdate($id, $data)
    {
        //return $data;
        $product = Product::findOrFail($id);
        // Category and Subcategory set
        $product->product_name = $data->product_name ?? $product->product_name;
        //$product->slug = Str::slug($data->product_name . '-' . $product->product_number . '-' . $product->ean_number);
        $product->category_id = $data->category_id;
        $product->subcategory_id = $data->subcategory_id;
        // Profit set
        $price = $product->price;
        $calculatedValues = Product::calculateProfitForUpdateProduct($price, $data->sale_price);
        $product->profit_type = "FLAT";
        $product->profit = $data->profit ?? 0;
        $product->profit_amount = round($calculatedValues, 2);
        $product->sale_price = round($data->sale_price) ?? 0; // sale price as frontend send
        // Description and Specification set
        $product->product_description = $data->description;
        $product->product_specification = $data->specification;
        $product->storage = $data->storage;
        $product->color = $data->color;
        $product->product_type = $data->product_type;
        $product->active_status = $data->active_status;
        $product->is_new_arrival = $data->is_new_arrival ?? 0;
        $product->is_best_selling = $data->is_best_selling ?? 0;
        $product->updated_by = Auth::id();
        $product->updated_at = now();
        $product->save();

        if (!empty($data->colors) && count($data->colors)) {
            $colors = array_map('intval', $data->colors);
            ProductColor::whereIn('product_id', [$product->id])->delete();
            foreach ($colors as $color) {
                $product_color = new ProductColor();
                $product_color->product_id = $product->id;
                $product_color->color_id = $color;
                $product_color->save();
            }
        }

        if (!empty($data->sizes) && count($data->sizes)) {
            $sizes = array_map('intval', $data->sizes);
            ProductSize::whereIn('product_id', [$product->id])->delete();
            foreach ($sizes as $size) {
                $product_size = new ProductSize();
                $product_size->product_id = $product->id;
                $product_size->size_id = $size;
                $product_size->save();
            }
        }


        if (isset($data->images)) {
            foreach ($data->images as $image) {
                if (array_key_exists('id', $image)) {
                    $product_image = ProductImage::find($image['id']);
                    if (!isset($product_image)) {
                        $product_image = new ProductImage();
                    }
                } else {
                    $product_image = new ProductImage();
                }

                $product_image->product_id = $id;
                $product_image->is_thumbnail = $image['is_thumbnail'];

                if (isset($image['image']) && $image['image'] instanceof \Illuminate\Http\UploadedFile) {
                    $product_image->image = uploadImage($image['image'], 'product');
                }

                $product_image->save();
            }
        }

        if ($data->active_status == 1 && $data->sale_price > 0) {
            setBusinessProductPrice($product->id, $data->active_status);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Product information has been updated successfully'
        ]);
    }

    public function downloadProductAsXl()
    {
        $data = Product::query();
        $data->active();
        if ($category_id = request()->get('category')) {
            $data->where('category_id', $category_id);
        }

        if ($subcategory_id = request()->get('subcategory')) {
            $data->where('subcategory_id', $subcategory_id);
        }

        if ($brand_id = request()->get('brand')) {
            $data->where('brand_id', $brand_id);
        }
        return Excel::download(new ProductsExport($data->get()), 'products.xlsx');
    }

    public function updateProductFromXl($data)
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0); // Remove the execution time limit

        $file = $data->file('xl_file');
        $filePath = $file->store('temp'); // Store the file temporarily
        $updated_by = Auth::id();
        // Dispatch the job
        BulkProductUpdate::dispatch($filePath,$updated_by);

        return response()->json([
            'status' => 'success',
            'message' => 'The product price update job has been queued successfully'
        ]);
    }


}
