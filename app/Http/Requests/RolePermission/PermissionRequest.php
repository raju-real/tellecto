<?php

namespace App\Http\Requests\RolePermission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return isSuper();
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                Rule::unique('permissions')->whereNull('deleted_at')
                    ->where('parent_id', $this->input('parent_id'))
                    ->when($this->isMethod('put'), function ($rule) {
                        $rule->ignore($this->route()->parameter('permission'));
                    })
            ],

            'order_no' => 'nullable|numeric|gte:0',
            'path' => 'nullable',
            'path_group' => 'nullable|required_if:type,Tab',
            'backend_path' => 'nullable',
            'icon' => 'nullable',
            'parent_id' => [
                'nullable',
                Rule::exists('permissions', 'id')->whereNull('deleted_at'),
                Rule::when(
                    $this->isMethod('put'),
                    [Rule::notIn([$this->route()->parameter('permission')])]
                )
            ],
            'type' => 'required|in:Side Bar,Tab',
            'permission_action' => 'array|nullable',
            'permission_action.*.id' => [
                'nullable',
                'numeric',
                'distinct',
                Rule::exists('permission_actions', 'id')->whereNull('deleted_at')
            ],
            'permission_action.*.permission_id' => [
                'nullable',
                'numeric',
                Rule::exists('permissions', 'id')->whereNull('deleted_at')
            ],
            'permission_action.*.action_id' => [
                'nullable',
                'numeric',
                Rule::exists('actions', 'id')->whereNull('deleted_at')
            ],
            'permission_action.*.path' => 'nullable',
            'permission_action.*.method' => 'nullable|in:get,put,post,delete',
            'permission_action.*.tooltip' => 'nullable',
        ];
    }
}
