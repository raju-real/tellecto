<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Services\Common\SubCategoryService;

class SubCategoryController extends Controller
{
    protected SubCategoryService $subCategoryService;

    public function __construct(SubCategoryService $subCategoryService)
    {
        $this->subCategoryService = $subCategoryService;
    }

    public function index()
    {
        try {
            return $this->subCategoryService->fetchAllSubCategory();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }


}
