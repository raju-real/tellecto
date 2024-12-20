<?php

namespace App\Http\Requests\Admin;

use App\Rules\ArrayLengthsMatch;
use Illuminate\Foundation\Http\FormRequest;

class ManualStatusRequest extends FormRequest
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
            'product_numbers' => ['required', 'array'],
            'product_numbers.*' => ['required', 'exists:products,product_number'],
            'active_status' => ['required', 'array'],
            'active_status.*' => ['required', 'in:0,1'],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        // Ensure product_numbers and active_status are both arrays before applying custom length check
        $validator->after(function ($validator) {
            $data = $this->all();

            if (isset($data['product_numbers']) && is_array($data['product_numbers']) &&
                isset($data['active_status']) && is_array($data['active_status'])) {
                $validator->addRules([
                    'product_numbers' => [new ArrayLengthsMatch(['product_numbers', 'active_status'])],
                    'active_status' => [new ArrayLengthsMatch(['active_status', 'product_numbers'])]
                ]);
            }
        });
    }
}
