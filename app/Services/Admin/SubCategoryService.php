<?php

namespace App\Services\Admin;

use App\Models\SubCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

/**
 * Class SubCategoryService.
 */
class SubCategoryService
{
    public function fetchAllSubCategory(): JsonResponse
    {
        $data = SubCategory::query();
        $data->when(request()->get('name'), function ($query) {
            $name = request()->get('name');
            $query->where('name', "LIKE", "%{$name}%");
        });
        $data->when(request()->get('category_id'), function ($query) {
            $query->where('category_id', request()->get('category_id'));
        });
        $data->when(request()->get('slug'), function ($query) {
            $query->where('slug', request()->get('slug'));
        });
        if(request()->has('active_status')) {
            $data->where('active_status', request()->get('active_status'));
        }
        $data->select('id', 'name', 'slug','thumbnail','original', 'category_id', 'active_status');
        $data->with([
            'category' => function ($query) {
                $query->select('id', 'name', 'slug');
            }
        ]);
        $data->orderBy('name');
        $Subcategories = $data;
        return paginationResponse('success', 200, $Subcategories, request('showPerPage'));

    }

    public function getSubByCatId($catId)
    {
        $data = SubCategory::query();
        $data->where('category_id', $catId);
        $data->where('active_status',1);
//        $data->whereHas('products', function ($query) {
//             $query->where('active_status', 1); // Ensure products are active
//         });

        $data->when(request()->get('name'), function ($query) {
            $name = request()->get('name');
            $query->where('name', 'LIKE', "%{$name}%");
        });

        $data->select('id', 'name', 'slug', 'thumbnail','original','category_id', 'active_status');
        $data->orderBy('name');

        $subcategories = $data->get();

        return apiResponse('success', 200, $subcategories);
    }

    public function getSubcategoryBySlug($slug)
    {
        $subcategory = SubCategory::where('slug', $slug)->select('id', 'name', 'slug', 'thumbnail','original','category_id', 'active_status')->get();
        return apiResponse('success', 200, $subcategory);
    }

    public function updateSubCategory($id, $requestData): JsonResponse
    {
        $row = SubCategory::findOrFail($id);
        $row->name = $requestData->name;
        $row->slug = Str::slug($requestData->name);
        if ($requestData->file('thumbnail')) {
            $row->thumbnail = uploadImage($requestData->file('thumbnail'), 'category');
        }
        if ($requestData->file('original')) {
            $row->original = uploadImage($requestData->file('original'), 'category');
        }
        $row->category_id = $requestData->category_id;
        $row->active_status = $requestData->active_status;
        if($row->save()) {
            return updateResponse('success',200,$row);
        } else {
            return failedResponse();
        }
    }

    public function setSubCategoryStatusGlobal($requestData)
    {
        if (SubCategory::query()->update(['active_status' => $requestData->active_status])) {
            return response()->json([
                'status' => 'success',
                'message' => 'Subcategory status has been updated successfully!'
            ]);
        } else {
            return failedResponse();
        }
    }

    public function subCategoryById($id): JsonResponse
    {
        $business = SubCategory::find($id);
        return apiResponse('success', 200, $business);
    }
}
