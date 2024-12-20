<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Services\Common\ProductService;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function getActiveProducts()
    {
        try {
            return $this->productService->getActiveProducts();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function productDetails($slug)
    {
        try {
            return $this->productService->productDetails($slug);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function productByBrands($slug)
    {
        try {
            return $this->productService->productByBrands($slug);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function brandBySlug($slug)
    {
        try {
            return $this->productService->brandBySlug($slug);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }
}
