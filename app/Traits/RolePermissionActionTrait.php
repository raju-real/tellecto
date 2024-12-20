<?php

namespace App\Traits;

use App\Models\RolePermission\RolePermissionAction;

trait RolePermissionActionTrait
{
    public function create_role_permission_action($permission_action, $role_id = null)
    {
        $role_permission_action_data = [
            ...$permission_action,
            'created_by' => auth()->id(),
        ];
        if (!is_null($role_id)) {
            $role_permission_action_data['role_id'] = $role_id;
        }
        return RolePermissionAction::create($role_permission_action_data);
    }

    public function update_role_permission_action($permission_action, $role_id = null)
    {
        $role_permission_action_data = [
            ...$permission_action,
            'updated_by' => auth()->id()
        ];
        if (!is_null($role_id)) {
            $role_permission_action_data['role_id'] = $role_id;
        }
        return tap(RolePermissionAction::findOrFail($permission_action['id']))->update($role_permission_action_data);
    }
}
