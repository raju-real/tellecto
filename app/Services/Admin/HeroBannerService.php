<?php

namespace App\Services\Admin;

use App\Models\HeroBanner;
use Illuminate\Http\JsonResponse;

/**
 * Class HeroBannerService.
 */
class HeroBannerService
{
    public function fetchAllHeroBanner(): JsonResponse
    {
        $data = HeroBanner::query();
        $data->when(request()->get('title'), function ($query) {
            $title = request()->get('title');
            $query->where('title', "LIKE", "%{$title}%");
        });

        if (request()->has('active_status')) {
            $data->where('active_status', request()->get('active_status'));
        }
        $data->select('id', 'title', 'link', 'image','banner_type','order_no', 'active_status');
        $data->latest();
        return paginationResponse('success', 200, $data, request('showPerPage'));
    }

    public function storeHeroBanner($requestData)
    {
        $row = new HeroBanner();
        $row->title = $requestData->title;
        $row->link = $requestData->link;
        $row->banner_type = $requestData->banner_type;
        $row->order_no = $requestData->order_no;
        $row->active_status = $requestData->active_status;
        if ($requestData->file('image')) {
            $row->image = uploadImage($requestData->file('image'), 'banner');
        }
        if ($row->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Information has been saved successfully!'
            ]);
        } else {
            return failedResponse();
        }
    }

    public function updateHeroBanner($id, $requestData)
    {
        $row = HeroBanner::findOrFail($id);
        $row->title = $requestData->title;
        $row->link = $requestData->link;
        $row->banner_type = $requestData->banner_type;
        $row->order_no = $requestData->order_no;
        $row->active_status = $requestData->active_status;
        if ($requestData->file('image')) {
            $row->image = uploadImage($requestData->file('image'), 'banner');
        }
        if ($row->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Information has been updated successfully!'
            ]);
        } else {
            return failedResponse();
        }

    }

    public function bannerById($id): JsonResponse
    {
        return apiResponse('success', 200, HeroBanner::select('id', 'title', 'link', 'image','banner_type','order_no', 'active_status')->findOrFail($id));
    }

    public function deleteHeroBanner($id)
    {
        HeroBanner::findOrFail($id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Information has been deleted successfully!'
        ]);
    }
}
