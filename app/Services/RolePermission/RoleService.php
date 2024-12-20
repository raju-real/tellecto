<?php

namespace App\Services\RolePermission;

use App\Models\RolePermission\Role;
use App\Models\RolePermission\RolePermissionAction;
use App\Services\ResponseService;
use App\Traits\RolePermissionActionTrait;
use Illuminate\Http\JsonResponse;

class RoleService
{
    use RolePermissionActionTrait;

    public function fetchList(): JsonResponse
    {

        $data = Role::query();
        $data->when(request()->get('name'), function ($query) {
            $name = request()->get('name');
            $query->where('name', "LIKE", "%{$name}%");
        });
        $data->when(request('per_page') > 0, function ($query) {
            $query->selectedColumn();
        });

        $data->orderBy('name');
        $roles = $data;
        return paginationResponse('success', 200, $roles);
    }

    public function storeRole($data): JsonResponse
    {
        $role = Role::create([
            ...$data->safe()->all(),
            'created_by' => auth()->id()
        ]);
        return ResponseService::createSuccessResponse($role, 'Role');
    }

    public function updateRole($request, $role): JsonResponse
    {

        if ($role->getAttribute('name') != $role->getAttribute('type')) {
            $role = tap($role)->update([
                ...$request->safe()->all(),
                'updated_by' => auth()->id()
            ]);
        }

        RolePermissionAction::where('role_id', $role->id)
            ->whereNotIn('permission_action_id',
                $request->collect('role_permission_action')->whereNull('permission_action_id')
                    ->pluck('permission_action_id')
                    ->toArray()
            )->delete();

        $request->collect('role_permission_action')
            ->each(function ($role_permission_action) use ($role) {
                if (array_key_exists('id', $role_permission_action)) {
                    $this->update_role_permission_action($role_permission_action, $role->id);
                } else {
                    $this->create_role_permission_action($role_permission_action, $role->id);
                }
            });
        return ResponseService::updateSuccessResponse($role, 'Role');

    }

    public function roleById($id): JsonResponse
    {
        $data = $this->findRole($id);

        if ($data) {
            return showResponse('success', 200, $data);
        } else {
            return failedResponse();
        }

    }

    public function deleteRole(int $id): JsonResponse
    {
        return notAvailableResponse("success", $id);
//        $row = $this->findRole($id);
//
//        if ($row->delete()) {
//            return deleteResponse('success', 200);
//        } else {
//            return failedResponse();
//        }
    }

    public function getAll(): JsonResponse
    {
        $data = Role::query();
        $data->when(request()->get('type'), function ($query) {
            $query->where('type', request()->get('type'));
        });
        $data->orderBy('name');
        $data->select('id', 'name');
        $results = $data->get();
        return getAllResponse('success', 200, $results);
    }

    public function status(int $id): JsonResponse
    {
        $row = $this->findRole($id);
        if ($row) {
            $row->status = !$row->status;
            $row->save();
            return updateResponse('success', 200);
        } else {
            return failedResponse();
        }
    }


    private function findRole($id): Role
    {
        return Role::findOrFail($id);

    }

}
