<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('admin_roles')->ignore($this->route('admin-role'))
            ],
            'description' => 'nullable|sometimes|max:2000',
            'activity_ids' => 'required|array|min:1',
            'activity_ids.*' => 'int|exists:admin_menu_activities,id'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The Role Name field is required.',
            'name.string' => 'The Role Name must be a string.',
            'name.unique' => $this->route('admin-role') ? 'The Role Name already exists.' : 'The Role Name must be unique.',
            'name.max' => 'The Role Name must not be greater than 100 characters.',
            'description.max' => 'The Role Description must not be greater than 2000 characters.',
        ];
    }
}
