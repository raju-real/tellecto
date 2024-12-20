<?php

namespace App\Services\Admin;

use App\Models\Brand;
use Illuminate\Http\JsonResponse;

/**
 * Class BrandService.
 */
class BrandService
{
    public function fetchAllBrand(): JsonResponse
    {
        $data = Brand::query();
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
        $data->select('id','name', 'slug', 'thumbnail','original','active_status');
        $data->orderBy('name');
        $brand = $data;
        return paginationResponse('success', 200, $brand, request('showPerPage'));
    }

    public function allBrand()
    {
        $data = Brand::query()->orderBy('name')->get();
        return apiResponse('success', 200, $data);
    }

    public function getBrandBySlug($slug)
    {
        $data = Brand::where('slug',$slug)->select('id','name', 'slug', 'thumbnail','original','active_status')->first();
        return apiResponse('success', 200, $data);
    }


    public function updateBrand($id,$requestData) {
        $row = Brand::findOrFail($id);
        $row->name = $requestData->name;
        $row->active_status = $requestData->active_status;
        if ($requestData->file('thumbnail')) {
            $row->thumbnail = uploadImage($requestData->file('thumbnail'), 'brand');
        }
        if ($requestData->file('original')) {
            $row->original = uploadImage($requestData->file('original'), 'brand');
        }

        if($row->save()) {
            return updateResponse('success',200,$row);
        } else {
            return failedResponse();
        }
    }

    public function setBrandStatusGlobal($requestData): JsonResponse
    {
        if (Brand::query()->update(['active_status' => $requestData->active_status])) {
            return response()->json([
                'status' => 'success',
                'message' => 'Brand status has been updated successfully!'
            ]);
        } else {
            return failedResponse();
        }
    }
    public function brandById($id): JsonResponse
    {
        $business = Brand::find($id);
        return apiResponse('success', 200, $business);
    }
}
