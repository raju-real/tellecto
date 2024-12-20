<?php

namespace App\Http\Requests\Business;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return isBusiness();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id=auth()->user()->id;

        $rules = [

//            'company_name' => [
//                'required',
//                'string',
//                'max:100'
//            ],
//            'org_no' => ['required', 'string', 'max:50'],
//            'vat_no' => ['required', 'string', 'max:50'],
//            'contact_person' => ['required', 'string', 'max:50'],
//            'business_type' => ['required', 'in:B2B,B2C,TELCO,OTHERS'],
//            'website_url' => ['nullable', 'sometimes', 'max:1000'],
//            'first_name' => ['nullable', 'sometimes', 'max:100'],
//            'last_name' => ['nullable', 'sometimes', 'max:100'],
           'name'=>'required',
            'mobile' => [
                'nullable',
                'sometimes',
                'max:50',
                Rule::unique('users')->ignore($id)->whereNull('deleted_at')
            ],
            'email' => [
                'required',
                'email',
                'max:50',
                Rule::unique('users')->ignore($id)->whereNull('deleted_at')
            ]
//            'logo' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
//            'street' => ['required', 'sometimes', 'max:50'],
//            'city' => ['required', 'sometimes', 'max:50'],
//            'zip_code' => ['required', 'sometimes', 'max:5'],
        ];


        if (!$this->id) {
            $rules['password'] = 'nullable | min:8';
            $rules['confirm_password'] = 'nullable | same:password';
        }
        return $rules;

    }

}
