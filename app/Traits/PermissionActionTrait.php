<?php

namespace App\Traits;

use App\Models\RolePermission\PermissionAction;

trait PermissionActionTrait
{
    public function create_permission_action($permission_action, $permission_id = null)
    {
        $permission_action_data = [
            ...$permission_action,
            'created_by' => auth()->id(),
        ];
        if (!is_null($permission_id)) {
            $permission_action_data['permission_id'] = $permission_id;
        }
        return PermissionAction::create($permission_action_data);
    }

    public function update_permission_action($permission_action, $permission_id = null)
    {
        $permission_action_data = [
            ...$permission_action,
            'updated_by' => auth()->id()
        ];
        if (!is_null($permission_id)) {
            $permission_action_data['permission_id'] = $permission_id;
        }
        return tap(PermissionAction::findOrFail($permission_action['id']))->update($permission_action_data);
    }

    public function update_or_create_permission_action($permission_action, $permission_id)
    {
        return PermissionAction::updateOrCreate(
            [
                'action_id' => $permission_action['action_id'],
                'permission_id' => $permission_id
            ],
            [
                ...$permission_action
            ]
        );
    }
}
