<?php

namespace App\Http\Requests\Admin;

use App\Rules\ProfitRatioRule;
use Illuminate\Foundation\Http\FormRequest;

class SingleProductProfitRequest extends FormRequest
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
            'id' => ['required', 'exists:products,id'],
            'profit_type' => ['required', 'in:FLAT,PERCENTAGE'],
//            'profit' => ['required', new ProfitRatioRule()]
            'profit' => ['required']
        ];
    }
}
