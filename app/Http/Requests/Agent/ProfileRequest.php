<?php

namespace App\Http\Requests\Agent;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->guard('agent')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id=auth()->guard('agent')->user()->id;
        $rules = [

            'manager_name' => [
                'nullable',
                'string',
                'max:100',
            ],
            'first_name' => [
                'required',
                'string',
                'max:100',
            ],
            'last_name' => [
                'required',
                'string',
                'max:100',
            ],
            'phone' => [
                'required',
                'string',
                'max:50',
                Rule::unique('agents')->ignore($id)->whereNull('deleted_at')
            ],
            'email' => [
                'required',
                'email',
                'max:50',
                Rule::unique('agents')->ignore($id)->whereNull('deleted_at')
            ],
            'image' => ['nullable','image','mimes:jpg,jpeg,png','max:5120'],
            'street' => [
                'nullable',
                'string',
                'max:100',
            ],
            'city' => [
                'nullable',
                'string',
                'max:100',
            ],
            'zip_code' => [
                'nullable',
                'string',
                'max:100',
            ],

            'status' => ['required', 'in:0,1'],
        ];

        if (!$this->id) {
            $rules['old_password'] = 'nullable | min:6';
            $rules['password'] = 'nullable | min:8';
            $rules['confirm_password'] = 'nullable | same:password';
        }
        return $rules;
    }
}
