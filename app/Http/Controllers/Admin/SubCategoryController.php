<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SubCategoryRequest;
use App\Services\Admin\SubCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{

    protected SubCategoryService $subCategoryService;

    public function __construct(SubCategoryService $subCategoryService)
    {
        $this->subCategoryService = $subCategoryService;
    }

    public function getAllSubCategoryByCatId($catId)
    {
        try {
            return $this->subCategoryService->getSubByCatId($catId);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return $this->subCategoryService->fetchAllSubCategory();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function getSubcategoryBySlug($slug): JsonResponse
    {
        try {
            return $this->subCategoryService->getSubcategoryBySlug($slug);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return $this->subCategoryService->subCategoryById($id);
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
    public function update(SubCategoryRequest $request, string $id): JsonResponse
    {
        try {
            return $this->subCategoryService->updateSubCategory($id,$request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function setSubCategoryStatusGlobal(SubCategoryRequest $request): JsonResponse
    {
        try {
            return $this->subCategoryService->setSubCategoryStatusGlobal($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

}
