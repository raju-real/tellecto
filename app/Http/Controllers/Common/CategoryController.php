<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Services\Common\CategoryService;

class CategoryController extends Controller
{
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function allCategory()
    {
        try {
            return $this->categoryService->getAll();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

}
