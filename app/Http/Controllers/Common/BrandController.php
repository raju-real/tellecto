<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\PublicMessageRequest;
use App\Services\Common\BrandService;

class BrandController extends Controller
{
    protected BrandService $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    public function allBrand()
    {
        try {
            return $this->brandService->allBrand();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function allHeroBanner()
    {
        try {
            return $this->brandService->allHeroBanner();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function allColor()
    {
        try {
            return $this->brandService->allColor();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function allSize()
    {
        try {
            return $this->brandService->allSize();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function allType()
    {
        try {
            return $this->brandService->allType();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }
    public function show(string $id)
    {
        try {
            return $this->brandService->brandById($id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function sendPublicMessage(PublicMessageRequest $request)
    {
        try {
            return $this->brandService->sendPublicMessage($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }


}
