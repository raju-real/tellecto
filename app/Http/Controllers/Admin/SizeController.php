<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SizeRequest;
use App\Services\Admin\SizeService;

class SizeController extends Controller
{
    protected SizeService $sizeService;

    public function __construct(SizeService $sizeService)
    {
        $this->sizeService = $sizeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return $this->sizeService->fetchAllSize();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function allSize()
    {
        try {
            return $this->sizeService->allSize();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function show($id)
    {
        try {
            return $this->sizeService->sizeDetails($id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SizeRequest $request)
    {
        try {
            return $this->sizeService->storeSize($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SizeRequest $request, string $id)
    {
        try {
            return $this->sizeService->updateSize($id,$request);
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
            return $this->sizeService->deleteSize($id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }
}
