<?php

namespace App\Http\Requests\Admin;

use App\Models\OrderItem;
use App\Rules\CheckMethodWeightLimit;
use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        // Fetch the order items (product_id and quantity) for weight calculation
        $orderItems = OrderItem::where('order_id', $this->route('order_id'))->get();

        return [
            'delivery_id' => [
                'required',
                'exists:delivery_charges,id',
                new CheckMethodWeightLimit($orderItems),
            ]
        ];
    }

    public function messages()
    {
        return [
            'delivery_id.required' => 'Delivery method is required.',
            'delivery_id.exists' => 'The selected delivery method is invalid.',
        ];
    }
}
