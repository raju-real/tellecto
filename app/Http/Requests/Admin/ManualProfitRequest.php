<?php

namespace App\Http\Requests\Admin;

use App\Rules\ArrayLengthsMatch;
use App\Rules\ProfitRatioRule;
use Illuminate\Foundation\Http\FormRequest;

class ManualProfitRequest extends FormRequest
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
            'product_numbers' => ['required','array', new ArrayLengthsMatch(['profit_types', 'profits'])],
            'product_numbers.*' => ['required','exists:products,product_number'],
            'profit_types' => ['required','array', new ArrayLengthsMatch(['product_numbers', 'profits'])],
            'profit_types.*' => ['required','in:FLAT,Percentage'],
            'profits' => ['required','array', new ArrayLengthsMatch(['product_numbers', 'profit_types'])],
            'profits.*' => ['required', new ProfitRatioRule()],
        ];
    }
}
