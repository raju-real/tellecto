<?php

namespace App\Http\Requests\Admin;

use App\Rules\ProfitRatioRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductWiseProfitRequest extends FormRequest
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
            '*.id' => [
                'required',
                'integer',
                Rule::exists('products', 'id'),
            ],
            '*.profit_type' => [
                'required',
                Rule::in(['FLAT', 'PERCENTAGE']),
            ],
            '*.profit' => ['required'],
//            '*.profit' => ['required', new ProfitRatioRule()],
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
        $validator->after(function ($validator) {
            $data = $this->all();
            foreach ($data as $index => $item) {
                if (
                    !isset($item['id']) ||
                    !isset($item['profit_type']) ||
                    !isset($item['profit'])
                ) {
                    $validator->errors()->add("data.{$index}", "The object at index {$index} must contain product_number, profit_type, and profit fields.");
                }
            }
        });
    }
}

