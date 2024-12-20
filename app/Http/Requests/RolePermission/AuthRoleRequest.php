<?php

namespace App\Http\Requests\RolePermission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class AuthRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return isSuper();
    }

    public function rules(): array
    {
        return [

        ];
    }

    protected function prepareForValidation(): void
    {
        $result = DB::select('call get_auth_permission_ids(?)', [auth()->id()]);
        request()->merge([
            'db_auth_permission_ids' => collect($result)->pluck('id')->toArray()
        ]);
    }
}
