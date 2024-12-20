<?php

namespace App\Services\RolePermission;

use App\Models\RolePermission\Action;
use Illuminate\Http\JsonResponse;

class ActionService
{
    public function fetchList(): JsonResponse
    {

//        $data = Action::selectedColumn();
//        $data->when(request()->get('name'), function ($query) {
//            $name = request()->get('name');
//            $query->where('name', "LIKE", "%{$name}%");
//        });
//        $data->orderBy('name');
//        $roles = $data;
//        return paginationResponse('success', 200, $roles);

        $data = Action::selectedColumn()
            ->when(request()->get('name'), function ($query) {
                $name = request()->get('name');
                $query->where('name', "LIKE", "%{$name}%");
            })
            ->orderBy('name', 'ASC');

        $actions = $data;
        return paginationResponse('success', 200, $actions);
    }

    public function save($data): JsonResponse
    {

        $action = Action::create([
            ...$data->safe()->all(),
            'created_by' => auth()->id()
        ]);
        return successResponse('success', 200, $action);

    }

    public function update($data, $id): JsonResponse
    {
        $action = tap($id)->update([
            ...$data->safe()->all(),
            'updated_by' => auth()->id()
        ]);
        return updateResponse('success', 200, $action);
    }


    public function getAll(): JsonResponse
    {
        $data = Action::query()->orderBy('name')->select('id', 'name')->where('status', 1)->get();
        return getAllResponse('success', 200, $data);
    }


}
