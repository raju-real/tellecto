<?php

namespace App\Services\Admin;

use App\Models\Color;
use App\Models\ProductColor;
use Illuminate\Support\Str;

/**
 * Class ColorService.
 */
class ColorService
{
    public function fetchAllColor()
    {
        $data = Color::query();
        $data->when(request()->get('color_name'), function ($query) {
            $color = request()->get('color_name');
            $query->where('color_name', "LIKE", "%{$color}%");
        });
        $data->orderBy('color_name');
        $data->select('id','color_name','color_code','slug');
        return paginationResponse('success', 200, $data, request('showPerPage'));
    }

    public function allColor()
    {
        $data = Color::orderBy('color_name')->select('id','color_name','color_code','slug')->get();
        return apiResponse('success', 200, $data);
    }

    public function storeColor($requestData)
    {
        $color = new Color();
        $color->color_name = $requestData->color_name;
        $color->color_code = $requestData->color_code;
        $color->slug = Str::slug($requestData->color_name);
        $color->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Information has been saved successfully!'
        ]);
    }

    public function colorDetails($id)
    {
        return response()->json([
            'status' => 'success',
            'data' => Color::select('id','color_name','color_code')->findOrFail($id)
        ]);
    }

    public function updateColor($id,$requestData)
    {
        $color = Color::findOrFail($id);
        $color->color_name = $requestData->color_name;
        $color->color_code = $requestData->color_code;
        $color->slug = Str::slug($requestData->color_name);
        $color->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Information has been updated successfully!'
        ]);
    }

    public function deleteColor($id)
    {
        ProductColor::whereIn('color_id',[$id])->delete();
        Color::where('id',$id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Information has been deleted successfully!'
        ]);
    }
}
