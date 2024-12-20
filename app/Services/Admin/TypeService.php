<?php

namespace App\Services\Admin;

use App\Models\ProductType;
use App\Models\Type;
use Illuminate\Support\Str;

/**
 * Class TypeService.
 */
class TypeService
{
    public function fetchAllType()
    {
        $data = Type::query();
        $data->when(request()->get('type_name'), function ($query) {
            $type = request()->get('type_name');
            $query->where('type_name', "LIKE", "%{$type}%");
        });
        $data->orderBy('type_name');
        $data->select('id','type_name','slug');
        return paginationResponse('success', 200, $data, request('showPerPage'));
    }

    public function allType()
    {
        $data = Type::orderBy('type_name')->select('id','type_name','slug')->get();
        return apiResponse('success', 200, $data);
    }

    public function storeType($requestData)
    {
        $type = new Type();
        $type->type_name = $requestData->type_name;
        $type->slug = Str::slug($requestData->type_name);
        $type->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Information has been saved successfully!'
        ]);
    }

    public function updateType($id,$requestData)
    {
        $type = Type::findOrFail($id);
        $type->type_name = $requestData->type_name;
        $type->slug = Str::slug($requestData->type_name);
        $type->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Information has been updated successfully!'
        ]);
    }

    public function deleteType($id)
    {
        ProductType::whereIn('type_id',[$id])->delete();
        Type::where('id',$id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Information has been deleted successfully!'
        ]);
    }
}
