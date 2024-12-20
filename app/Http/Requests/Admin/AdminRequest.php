<?php

namespace App\Http\Requests\Admin;

use App\Rules\AdminRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminRequest extends FormRequest
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
            'role_id' => ['required','int','exists:roles,id', new AdminRole()],
            'name' => [
                'required',
                'string',
                'max:100',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:50',
                Rule::unique('users')->ignore($this->route('admin'))
            ],
            'username' => [
                'nullable',
                'sometimes',
                'max:50',
                Rule::unique('users')->ignore($this->route('admin'))
            ],
            'mobile' => [
                'required',
                'max:50',
                Rule::unique('users')->ignore($this->route('admin'))
            ],
            'image' => 'nullable|sometimes|image|mimes:jpg,jpeg,png|max:1024',
            'password' => 'nullable|sometimes|min:8',
            'is_active' => 'nullable|sometimes|in:0,1'
        ];
    }
}
