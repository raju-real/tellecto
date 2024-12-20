<?php

namespace App\Http\Requests\Admin;

use App\Rules\ProfitRatioRule;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ShippingMethodRequest extends FormRequest
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
            'code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('delivery_charges')->ignore($this->route('shipping_method'))
            ],
            'dcs_charge' => [
                'required',
                new ProfitRatioRule()
            ],
            'delivery_type' => [
                'required',
                'string',
                'max:191',
                Rule::unique('delivery_charges')->ignore($this->route('shipping_method'))
            ],
            'delivery_dcs' => [
                'required',
                'string',
                'max:191',
                Rule::unique('delivery_charges')->ignore($this->route('shipping_method'))
            ],
            'delivery_charge' => [
                'required',
                new ProfitRatioRule()
            ],
            'vat_rate' => [
                'required',
                new ProfitRatioRule()
            ],
            'parcel_shop_status' => 'required|in:0,1',
            'description' => 'nullable|sometimes|max:2000,',
            'status' => 'required|in:0,1',
        ];
    }
}
