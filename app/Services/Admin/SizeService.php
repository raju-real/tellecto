<?php

namespace App\Services\Admin;

use App\Models\ProductSize;
use App\Models\Size;
use Illuminate\Support\Str;

/**
 * Class SizeService.
 */
class SizeService
{
    public function fetchAllSize()
    {
        $data = Size::query();
        $data->when(request()->get('size_name'), function ($query) {
            $size = request()->get('size_name');
            $query->where('size_name', "LIKE", "%{$size}%");
        });
        $data->orderBy('size_name');
        $data->select('id','size_name','slug');
        return paginationResponse('success', 200, $data, request('showPerPage'));
    }

    public function allSize()
    {
        $data = Size::orderBy('size_name')->select('id','size_name','slug')->get();
        return apiResponse('success', 200, $data);
    }

    public function storeSize($requestData)
    {
        $size = new Size();
        $size->size_name = $requestData->size_name;
        $size->slug = Str::slug($requestData->size_name);
        $size->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Information has been saved successfully!'
        ]);
    }

    public function sizeDetails($id)
    {
        return response()->json([
            'status' => 'success',
            'data' => Size::select('id','size_name')->findOrFail($id)
        ]);
    }

    public function updateSize($id,$requestData)
    {
        $size = Size::findOrFail($id);
        $size->size_name = $requestData->size_name;
        $size->slug = Str::slug($requestData->size_name);
        $size->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Information has been updated successfully!'
        ]);
    }

    public function deleteSize($id)
    {
        ProductSize::whereIn('size_id',[$id])->delete();
        Size::where('id',$id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Information has been deleted successfully!'
        ]);
    }
}
