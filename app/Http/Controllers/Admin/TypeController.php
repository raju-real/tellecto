<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TypeRequest;
use App\Services\Admin\TypeService;

class TypeController extends Controller
{
    protected TypeService $typeService;

    public function __construct(TypeService $typeService)
    {
        $this->typeService = $typeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return $this->typeService->fetchAllType();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function allType()
    {
        try {
            return $this->typeService->allType();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TypeRequest $request)
    {
        try {
            return $this->typeService->storeType($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TypeRequest $request, string $id)
    {
        try {
            return $this->typeService->updateType($id,$request);
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
            return $this->typeService->deleteType($id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }
}
