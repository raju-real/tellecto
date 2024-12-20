<?php

namespace App\Http\Requests\common;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'agent_id' => 'nullable',
            'address_type' => 'nullable',
            'street' => 'nullable',
            'city' => 'nullable',
            'address' => 'nullable',
            'zip_code' => 'nullable'
        ];

        return $rules;

    }

}
