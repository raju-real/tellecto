<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HeroBannerRequest;
use App\Services\Admin\HeroBannerService;

class HeroBannerController extends Controller
{
    protected HeroBannerService $heroBannerService;

    public function __construct(HeroBannerService $heroBannerService)
    {
        $this->heroBannerService = $heroBannerService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return $this->heroBannerService->fetchAllHeroBanner();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HeroBannerRequest $request)
    {
        try {
            return $this->heroBannerService->storeHeroBanner($request);
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
            return $this->heroBannerService->bannerById($id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HeroBannerRequest $request, string $id)
    {
        try {
            return $this->heroBannerService->updateHeroBanner($id,$request);
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
            return $this->heroBannerService->deleteHeroBanner($id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }
}
