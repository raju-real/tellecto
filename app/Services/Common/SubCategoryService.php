<?php

namespace App\Services\Common;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

/**
 * Class SubCategoryService.
 */
class SubCategoryService
{
    public function fetchAllSubCategory(): JsonResponse
    {
        $data = SubCategory::query();
        $data->active();
        $data->where('name', '!=', 'Unsubcategorized');
        $data->when(request()->get('name'), function ($query) {
            $name = request()->get('name');
            $query->where('name', "LIKE", "%{$name}%");
        });
        $data->when(request()->get('category_id'), function ($query) {
            $query->where('category_id', request()->get('category_id'));
        });
        $data->when(request()->get('category_slug'), function ($query) {
            $category_id = Category::whereSlug(request()->get('category_slug'))->first()->id;
            $query->where('category_id', $category_id);
        });
        $data->when(request()->get('slug'), function ($query) {
            $query->where('slug', request()->get('slug'));
        });
        $data->when(request()->get('active_status'), function ($query) {
            $query->where('active_status', request()->get('active_status'));
        });
        $data->select('id', 'name', 'slug', 'thumbnail', 'original', 'category_id', 'active_status');
        $data->with([
            'category' => function ($query) {
                $query->select('id', 'name', 'slug', 'thumbnail', 'original', 'active_status');
            }
        ]);
        $data->orderBy('name');
        $subcategories = $data->get();
        return apiResponse('success', 200, $subcategories);

    }

    public function getSubByCatId($catId)
    {
        $categories = SubCategory::query()->where('category_id', $catId)->select('id', 'name', 'slug', 'category_id', 'active_status')->get();
        return apiResponse('success', 200, $categories);
    }
}
