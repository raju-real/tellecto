<?php

namespace App\Http\Requests\Business;

use App\Rules\ProfitRatioRule;
use App\Rules\ValidBusinessProduct;
use Illuminate\Foundation\Http\FormRequest;

class SingleProductProfitRequest extends FormRequest
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
            'id' => [
                'required',
                new ValidBusinessProduct(),
            ],
            'profit_type' => ['required', 'in:FLAT,PERCENTAGE'],
            'profit' => ['required']
//            'profit' => ['required', new ProfitRatioRule()]
        ];
    }
}