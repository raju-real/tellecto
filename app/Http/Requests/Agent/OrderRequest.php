<?php

namespace App\Http\Requests\Agent;

use App\Models\Product;
use App\Rules\BusinessProductRule;
use App\Rules\DeliveryWeightLimitRule;
use App\Rules\ParcelShopRequired;
use App\Rules\ProductInventoryRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
{
    public function authorize()
    {
        // Authorize this request
        return true;
    }

    public function rules()
    {
        return [
            'cart' => 'required|array',
            'cart.items' => 'required|array|min:1',
            'cart.items.*.id' => [
                'required',
                'exists:products,id',
                new BusinessProductRule(),
            ],
            'cart.items.*.quantity' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[2];
                    $productId = $this->input("cart.items.$index.id");
                    $quantity = $value;

                    $rule = new ProductInventoryRule($productId, $quantity);
                    if (!$rule->passes($attribute, $value)) {
                        $fail("The product " . Product::find($productId)->product_name . " is out of stock or not has sufficient quantity!");
                        //$fail($rule->message());
                    }
                },
            ],
            'cart.items.*.price' => 'required|numeric',
            'cart.items.*.item_total' => 'required|numeric',
            'cart.total' => 'required|numeric',
            'cart.freight_cost' => 'required|numeric',
            'cart.vat_percentage' => 'required|numeric',
            'cart.comment' => 'nullable|string',
            'cart.address.*.address' => ['required', 'string', 'max:255'],
            'cart.address.*.zip_code' => ['required', 'string', 'max:20'],
            'cart.address.*.city' => ['required', 'string', 'max:100'],
            'delivery_id' => [
                'required',
                'exists:delivery_charges,id',
                // Apply the new custom rule to check weight limit
                new DeliveryWeightLimitRule($this->input('cart.items')),
            ],
            'parcel_shop' => [new ParcelShopRequired($this->input('delivery_id'))],
            // Use required_with to validate individual fields only if parcel_shop is present
//            'parcel_shop.service_point_id' => 'required_with:parcel_shop',
//            'parcel_shop.shop_name' => 'required_with:parcel_shop',
//            'parcel_shop.city' => 'required_with:parcel_shop',
//            'parcel_shop.street_name' => 'required_with:parcel_shop',
//            'parcel_shop.street_number' => 'required_with:parcel_shop',
//            'parcel_shop.postal_code' => 'required_with:parcel_shop',
            'payment' => 'required|string',
        ];
    }


    public function messages()
    {
        return [
            'cart.items.*.id.exists' => 'The selected product ID is invalid.'
        ];
    }
}
