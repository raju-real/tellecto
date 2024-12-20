<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
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
        $id = auth()->user()->id;

        $rules = [
            'name' => [
                'required',
                'string',
                'max:100',
            ],
            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users')->ignore($id)->whereNull('deleted_at')
            ],
            'mobile' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users')->ignore($id)->whereNull('deleted_at')
            ],
            'email' => [
                'required',
                'email',
                'max:50',
                Rule::unique('users')->ignore($id)->whereNull('deleted_at')
            ],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'role_id' => 'nullable',
            'is_active' => ['required', 'in:0,1'],
            'user_id' => 'nullable',
            'employee_id' => 'nullable',
            'company_name' => 'nullable',
            'joining_date' => 'nullable',
            'org_no' => 'nullable',
            'vat_no' => 'nullable',
            'contact_person' => 'nullable',
            'business_type' => 'nullable',
            'website_url' => 'nullable',
            'phone' => 'nullable',
            'company_email' => 'nullable',
            'logo' => 'nullable',
            'street' => 'nullable',
            'city' => 'nullable',
            'zip_code' => 'nullable',
        ];

        if (!$this->id) {
            $rules['old_password'] = 'nullable | min:6';
            $rules['password'] = 'nullable | min:8';
            $rules['confirm_password'] = 'nullable | same:password';
        }
        return $rules;
    }
}
