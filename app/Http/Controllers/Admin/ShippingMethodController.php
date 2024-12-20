<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\ShippingMethodService;
use App\Http\Requests\Admin\ShippingMethodRequest;

class ShippingMethodController extends Controller
{
    protected ShippingMethodService $shippingMethodService;

    public function __construct(ShippingMethodService $shippingMethodService)
    {
        $this->shippingMethodService = $shippingMethodService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return $this->shippingMethodService->fetchAllShippingMethod();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function allShippingMethod()
    {
        try {
            return $this->shippingMethodService->allShippingMethod();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ShippingMethodRequest $request)
    {
        try {
            return $this->shippingMethodService->storeShippingMethod($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function show($id)
    {
        try {
            return $this->shippingMethodService->shippingMethodDetails($id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ShippingMethodRequest $request, string $id)
    {
        try {
            return $this->shippingMethodService->updateShippingMethod($id,$request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            return $this->shippingMethodService->deleteShippingMethod($id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }
}
