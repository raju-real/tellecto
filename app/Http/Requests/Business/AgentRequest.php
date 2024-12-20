<?php

namespace App\Http\Requests\Business;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AgentRequest extends FormRequest
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
        $rules = [
            'agent_code' => [
                'nullable',
                'sometimes',
                'int',
                'digits:5',
                Rule::unique('agents')->ignore($this->route('agent'))->whereNull('deleted_at')
            ],

            'personal_id' => [
                'required',
                'int',
                'digits:12',
                Rule::unique('agents')->ignore($this->route('agent'))->whereNull('deleted_at')
            ],

//            'manager_name' => [
//                'required',
//                'string',
//                'max:100',
//            ],
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
                Rule::unique('agents')->ignore($this->route('agent'))->whereNull('deleted_at')
            ],
            'email' => [
                'required',
                'email',
                'max:50',
                Rule::unique('agents')->ignore($this->route('agent'))->whereNull('deleted_at')
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
            $rules['confirm_password'] = 'required | same:password';
            $rules['password'] = 'required | min:8';
        }
        return $rules;
    }
}
