<?php

namespace App\Http\Requests\Admin;

use App\Rules\BusinessRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BusinessRequest extends FormRequest
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
        $user_id = $this->route('business');
        $rules = [
            'role_id' => ['required','int','exists:roles,id', new BusinessRole()],
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
                Rule::unique('users')->ignore($user_id)
            ],
            'username' => [
                'nullable',
                'sometimes',
                'max:50',
                Rule::unique('users')->ignore($user_id)
            ],
            'mobile' => [
                'required',
                'max:50',
                Rule::unique('users')->ignore($user_id)
            ],
            'image' => 'nullable|sometimes|image|mimes:jpg,jpeg,png|max:1024',
            'password' => 'min:8',
            'is_active' => 'nullable|sometimes|in:0,1',
            'company_name' => ['required', 'string', 'max:100'],
            'org_no' => ['required', 'string', 'max:50'],
            'vat_no' => ['required', 'string', 'max:50'],
//            'contact_person' => ['required', 'string', 'max:50'],
//            'business_type' => ['required', 'in:B2B,B2C,TELCO,OTHERS'],
            'website_url' => ['nullable', 'sometimes', 'max:1000'],
//            'phone' => [
//                'nullable',
//                'sometimes',
//                'max:50',
//                Rule::unique('user_information')->ignore($user_id,'user_id')->whereNull('deleted_at')
//            ],
            'company_email' => [
                'required',
                'email',
                'max:50',
                Rule::unique('user_information')->ignore($user_id,'user_id')->whereNull('deleted_at')
            ],
            'logo' => ['nullable','sometimes','image','mimes:jpg,jpeg,png','max:5120'],
            'street' => ['required', 'sometimes', 'max:50'],
            'city' => ['required', 'sometimes', 'max:50'],
            'zip_code' => ['required', 'sometimes', 'max:5']
        ];

//        if (!$this->id) {
//            $rules['confirm_password'] = 'required|same:password';
//            $rules['password'] = 'required|min:6';
//        }

        return $rules;

    }
}
