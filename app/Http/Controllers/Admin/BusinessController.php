<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BusinessRequest;
use App\Http\Requests\Admin\PasswordChangeRequest;
use App\Services\Admin\BusinessService;

class BusinessController extends Controller
{
    protected BusinessService $businessService;

    public function __construct(BusinessService $businessService)
    {
        $this->businessService = $businessService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return $this->businessService->fetchAllBusiness();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BusinessRequest $request)
    {
        try {
            return $this->businessService->storeBusiness($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return $this->businessService->businessById($id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BusinessRequest $request, int $id)
    {
        try {
            return $this->businessService->updateBusiness($request, $id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            return $this->businessService->deleteBusiness($id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function changeBusinessStatus($id): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->businessService->changeBusinessStatus($id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function changeBusinessPassword(PasswordChangeRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->businessService->changeBusinessPassword($request, $id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }
    public function allBusinesses()
    {
        try {
            return $this->businessService->allBusiness();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }
}
