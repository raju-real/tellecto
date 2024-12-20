<?php

namespace App\Http\Resources;

use App\Models\RolePermission\Permission;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthPermissionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            ...(array)$this->attributes(collect((new Permission())->getFillable())->flip()
                ->except(['created_by', 'updated_by', 'deleted_by'])
                ->keys()
                ->merge('id')
                ->toArray()
            )->data,
            'auth_child_side_bar' => $this->auth_all_child_side_bar,
            'auth_child_tab' => $this->auth_all_child_tab,
            'permission_action' => $this->permission_action
        ];
    }
}
