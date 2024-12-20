<?php

namespace App\Services\Admin;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

/**
 * Class CategoryService.
 */
class CategoryService
{
    public function fetchAllCategory(): JsonResponse
    {

        $data = Category::query();
        $data->when(request()->get('name'), function ($query) {
            $name = request()->get('name');
            $query->where('name', "LIKE", "%{$name}%");
        });
        $data->when(request()->get('slug'), function ($query) {
            $query->where('slug', request()->get('slug'));
        });

        if(request()->has('active_status')) {
            $data->where('active_status', request()->get('active_status'));
        }
        $data->select('id', 'name', 'slug', 'thumbnail','original','is_mega','active_status','vat_type');
        $data->orderBy('name');
        $categories = $data;
        return paginationResponse('success', 200, $categories, request('showPerPage'));
    }


    public function categoryById($id): JsonResponse
    {
        $business = Category::find($id);
        return apiResponse('success', 200, $business);
    }

    public function getCategoryBySlug($slug): JsonResponse
    {
        $category = Category::where('slug',$slug)->select('id', 'name', 'slug', 'thumbnail','original','is_mega','active_status','vat_type')->first();
        return apiResponse('success', 200, $category);
    }

    public function getAll()
    {
        $data = Category::query();
        $data->where('active_status',1);
//        $data->whereHas('products', function ($query) {
//             $query->where('active_status', 1); // Ensure products are active
//         });

        $data->when(request()->get('name'), function ($query) {
            $name = request()->get('name');
            $query->where('name', 'LIKE', "%{$name}%");
        });

        $data->select('id', 'name');
        $data->orderBy('name');

        $categories = $data->get();

        return apiResponse('success', 200, $categories);

    }

    public function updateCategory($id,$requestData) {
        $row = Category::findOrFail($id);
        $row->name = $requestData->name;
        $row->slug = Str::slug($requestData->name);
        $row->active_status = $requestData->active_status;
        $row->is_mega = $requestData->is_mega;
        $row->vat_type = $requestData->vat_type;
        if ($requestData->file('thumbnail')) {
            $row->thumbnail = uploadImage($requestData->file('thumbnail'), 'category');
        }
        if ($requestData->file('original')) {
            $row->original = uploadImage($requestData->file('original'), 'category');
        }

        if($row->save()) {
            return updateResponse('success',200,$row);
        } else {
            return failedResponse();
        }
    }

    public function setCategoryStatusGlobal($requestData) {
        if (Category::query()->update(['active_status' => $requestData->active_status])) {
            return response()->json([
                'status' => 'success',
                'message' => 'Category status has been updated successfully!'
            ]);
        } else {
            return failedResponse();
        }
    }

}
