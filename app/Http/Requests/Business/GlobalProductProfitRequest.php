<?php

namespace App\Http\Requests\Business;

use App\Rules\ProfitRatioRule;
use Illuminate\Foundation\Http\FormRequest;

class GlobalProductProfitRequest extends FormRequest
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
        return [
            'profit_type' => ['required','in:FLAT,PERCENTAGE'],
            'profit' => ['required',new ProfitRatioRule()]
        ];
    }
}
