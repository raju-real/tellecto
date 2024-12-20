<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BulkCategoryRequest;
use App\Http\Requests\Admin\BulkProfitRequest;
use App\Http\Requests\Admin\BulkStatusRequest;
use App\Http\Requests\Admin\GlobalProductCategoryRequest;
use App\Http\Requests\Admin\GlobalProductProfitRequest;
use App\Http\Requests\Admin\ManualCategoryRequest;
use App\Http\Requests\Admin\ManualProductCategoryRequest;
use App\Http\Requests\Admin\ManualProductStatusRequest;
use App\Http\Requests\Admin\ManualProfitRequest;
use App\Http\Requests\Admin\ManualStatusRequest;
use App\Http\Requests\Admin\ProductDescriptionRequest;
use App\Http\Requests\Admin\ProductImageRequest;
use App\Http\Requests\Admin\ProductWiseProfitRequest;
use App\Http\Requests\Admin\SingleProductCategoryRequest;
use App\Http\Requests\Admin\SingleProductProfitRequest;
use App\Http\Requests\Admin\SingleProductStatusRequest;
use App\Http\Requests\Admin\SingleProductUpdateRequest;
use App\Http\Requests\Admin\updateProductFromXlRequest;
use App\Services\Admin\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function productList()
    {
        try {
            return $this->productService->productList();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function getActiveProducts()
    {
        try {
            return $this->productService->getActiveProducts();
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

    public function saveProduct()
    {
        try {
            return $this->productService->saveProducts();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function updateProduct()
    {
        try {
            $apiUrl=env("DCS_UPDATE_PRODUCT") ?? null;
            return $this->productService->updateProducts($apiUrl);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function productByNumber($product_number)
    {
        try {
            return $this->productService->productByNumber($product_number);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function productById($id)
    {
        try {
            return $this->productService->productById($id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function productBySlug($slug)
    {
        try {
            return $this->productService->productBySlug($slug);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function removeProductImages(Request $requestData)
    {
        try {
            return $this->productService->removeProductImages($requestData);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function setBulkProfit(BulkProfitRequest $request)
    {
        try {
            return $this->productService->setBulkProfit($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function setManualProfit(ManualProfitRequest $request)
    {
        try {
            return $this->productService->setManualProfit($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function setBulkStatus(BulkStatusRequest $request)
    {
        try {
            return $this->productService->setBulkStatus($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function setManualStatus(ManualStatusRequest $request)
    {
        try {
            return $this->productService->setManualStatus($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function setBulkCategory(BulkCategoryRequest $request)
    {
        try {
            return $this->productService->setBulkCategory($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function setManualCategory(ManualCategoryRequest $request)
    {
        try {
            return $this->productService->setManualCategory($request);
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

    public function setProductStatusGlobal(BulkStatusRequest $request)
    {
        try {
            return $this->productService->setBulkStatus($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function setProductCategoryGlobal(GlobalProductCategoryRequest $request)
    {
        try {
            return $this->productService->setProductCategoryGlobal($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function setProductWiseCategory(ManualProductCategoryRequest $request)
    {
        try {
            return $this->productService->setProductWiseCategory($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function setSingleProductCategory(SingleProductCategoryRequest $request)
    {
        try {
            return $this->productService->setSingleProductCategory($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function setProductWiseStatus(ManualProductStatusRequest $request) {
        try {
            return $this->productService->setProductWiseStatus($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function setSingleProductStatus(SingleProductStatusRequest $request) {
        try {
            return $this->productService->setSingleProductStatus($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function setProductDescription(ProductDescriptionRequest $request)
    {
        try {
            return $this->productService->setProductDescription($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function setProductImage(ProductImageRequest $request)
    {
        try {
            return $this->productService->setProductImage($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function singleProductUpdate(SingleProductUpdateRequest $request, $id) {
        try {
            return $this->productService->singleProductUpdate($id, $request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function downloadProductAsXl() {
        try {
            return $this->productService->downloadProductAsXl();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function updateProductFromXl(updateProductFromXlRequest $request) {
        try {
            return $this->productService->updateProductFromXl($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

}
