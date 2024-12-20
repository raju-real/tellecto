<?php

namespace App\Services\Admin;

use App\Models\AdminMenuActivity;
use App\Models\AdminRole;
use App\Models\AdminRolePermission;
use Illuminate\Http\JsonResponse;

/**
 * Class AdminRoleService.
 */
class AdminRoleService
{
    public function fetchAllRoles(): JsonResponse
    {

        $data = AdminRole::query();
        $data->when(request()->get('name'), function ($query) {
            $name = request()->get('name');
            $query->where('name', "LIKE", "%{$name}%");
        });
        $data->with([
            'permissions' => function ($permission) {
                $permission->select('id','role_id','menu_id','activity_id');
            }
        ]);
        $roles = $data;
        return paginationResponse('success', 200, $roles, 50);
    }

    public function storeAdminRole($data): JsonResponse
    {
        $role = new AdminRole();
        $role->name = $data->name;
        $role->description = $data->description;

        if ($role->save()) {
            $activity_ids = $data->activity_ids;
            if (isset($activity_ids)) {
                foreach ($activity_ids as $activity_id) {
                    $menu_id = AdminMenuActivity::find($activity_id)->menu_id;
                    $request_activity = [
                        'role_id' => $role->id,
                        'menu_id' => $menu_id,
                        'activity_id' => $activity_id
                    ];
                    AdminRolePermission::updateOrInsert($request_activity, $request_activity);
                }

                foreach (AdminMenuActivity::where('is_dependant', "Yes")->get() as $activity) {
                    $menu_id = AdminMenuActivity::find($activity->id)->menu_id;
                    $dependant_activity = [
                        'role_id' => $role->id,
                        'menu_id' => $menu_id,
                        'activity_id' => $activity->id
                    ];
                    AdminRolePermission::updateOrInsert($dependant_activity, $dependant_activity);
                }
            }
            return successResponse('success',200,$role);
        } else {
            return failedResponse();
        }
    }

    public function updateBusiness($data, $id): JsonResponse
    {
        $role = AdminRole::findOrFail($id);
        $role->name = $data->name;
        $role->description = $data->description;

        if ($role->save()) {
            $activity_ids = $data->activity_ids;
            if (isset($activity_ids)) {
                AdminRolePermission::whereIn('role_id',array($role->id))->delete();
                foreach ($activity_ids as $activity_id) {
                    $menu_id = AdminMenuActivity::find($activity_id)->menu_id;
                    $request_activity = [
                        'role_id' => $role->id,
                        'menu_id' => $menu_id,
                        'activity_id' => $activity_id
                    ];
                    AdminRolePermission::updateOrInsert($request_activity, $request_activity);
                }

                foreach (AdminMenuActivity::where('is_dependant', "Yes")->get() as $activity) {
                    $menu_id = AdminMenuActivity::find($activity->id)->menu_id;
                    $dependant_activity = [
                        'role_id' => $role->id,
                        'menu_id' => $menu_id,
                        'activity_id' => $activity->id
                    ];
                    AdminRolePermission::updateOrInsert($dependant_activity, $dependant_activity);
                }
            }
            return updateResponse('success',200,$role);
        } else {
            return failedResponse();
        }
    }

    public function deleteRole(int $id): JsonResponse
    {
        $role = AdminRole::findOrFail($id);
        AdminRolePermission::whereIn('role_id',array($role->id))->delete();
        if ($role->delete()) {
            return deleteResponse('success', 200);
        } else {
            return failedResponse();
        }
    }
}
