<?php

namespace App\Http\Requests\Business;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class businessRegisterRequest extends FormRequest
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
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:50',
                Rule::unique('users')
            ],
            'username' => [
                'nullable',
                'sometimes',
                'max:50',
                Rule::unique('users')
            ],
            'mobile' => [
                'required',
                'max:50',
                Rule::unique('users')
            ],
            'image' => 'nullable|sometimes|image|mimes:jpg,jpeg,png|max:1024',
            'password' => 'min:6,max:10',
            'is_active' => 'nullable|sometimes|in:0,1',
            'company_name' => ['required', 'string', 'max:100'],
            'org_no' => ['required', 'string', 'max:50'],
            'vat_no' => ['required', 'string', 'max:50'],
            //'contact_person' => ['required', 'string', 'max:50'],
            //'business_type' => ['required', 'in:B2B,B2C,TELCO,OTHERS'],
            'website' => ['nullable', 'sometimes', 'max:1000'],
//            'phone' => [
//                'nullable',
//                'sometimes',
//                'max:50',
//                Rule::unique('user_information')
//            ],
            'company_email' => [
                'required',
                'email',
                'max:50',
                Rule::unique('user_information')
            ],
            'logo' => ['nullable','sometimes','image','mimes:jpg,jpeg,png','max:5120'],
            'street' => ['required', 'sometimes', 'max:50'],
            'city' => ['required', 'sometimes', 'max:50'],
            'zip_code' => ['required', 'sometimes', 'max:5']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'company_name.required' => 'The company name field is required.',
            'company_email.required' => 'The company email field is required.',
            'phone.required' => 'The phone number field is required.',
            'company_email.unique' => 'This email is already registered.',
        ];
    }
}
