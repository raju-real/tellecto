<?php

namespace App\Services\RolePermission;

use App\Models\RolePermission\Permission;
use App\Models\RolePermission\PermissionAction;
use App\Services\ResponseService;
use App\Traits\PermissionActionTrait;
use Illuminate\Http\JsonResponse;

class PermissionService
{
    use PermissionActionTrait;

    public function fetchList(): JsonResponse
    {

        $data = Permission::selectedColumn()
            ->whereNull('parent_id')
            ->with('all_child', 'permission_action.action')
            ->when(request('search'), function ($query) {
                $query->whereRaw(request('search'));
            })
            ->orderBy('order_no', 'ASC');

        $permission = $data;
        return paginationResponse('success', 200, $permission);

    }

    public function storePermission($data): JsonResponse
    {
        $permission = Permission::create([
            ...$data->safe()->all(),
            'created_by' => auth()->id()
        ]);

        $data->collect('permission_action')->each(function ($permission_action) use ($data, $permission) {
            $this->create_permission_action($permission_action, $permission->id);
        });
        return ResponseService::createSuccessResponse($permission, 'Permission');
    }

    public function update($request, $permission): JsonResponse
    {
        $permission = tap($permission)->update([
            ...$request->safe()->all(),
            'updated_by' => auth()->id()
        ]);

        PermissionAction::where('permission_id', $permission->id)
            ->whereNotIn('action_id', $request->collect('permission_action')
                ->whereNotNull('action_id')
                ->pluck('action_id')
                ->toArray()
            )
            ->delete();

        $request->collect('permission_action')->each(function ($permission_action) use ($permission) {
            $this->update_or_create_permission_action($permission_action, $permission->id);
        });
        return ResponseService::updateSuccessResponse($permission, 'Permission');
    }


    public function getAll(): JsonResponse
    {
        $data = Permission::query()->orderBy('name')->select('id', 'name')->get();
        return getAllResponse('success', 200, $data);
    }


}
