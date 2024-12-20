<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Http\Requests\Business\GlobalProductProfitRequest;
use App\Http\Requests\Business\ProductWiseProfitRequest;
use App\Http\Requests\Business\SingleProductProfitRequest;
use App\Services\Business\ProductService;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function productLists()
    {
        try {
            return $this->productService->productList();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function fetchProduct()
    {
        try {
            return $this->productService->fetchProducts();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function updateProduct()
    {
        try {
            return $this->productService->updateProduct();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function setProductProfitGlobal(GlobalProductProfitRequest $request)
    {
        try {
            return $this->productService->setGlobalProductProfit($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function setProductWiseProfit(ProductWiseProfitRequest $request)
    {
        try {
            return $this->productService->setProductWiseProfit($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function setSingleProductProfit(SingleProductProfitRequest $request)
    {
        try {
            return $this->productService->setSingleProductProfit($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function showProductByIdForBusiness($id)
    {
        try {
            return $this->productService->showproductByIdForBusiness($id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }
}
