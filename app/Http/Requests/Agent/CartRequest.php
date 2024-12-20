<?php

namespace App\Http\Requests\Agent;

use App\Models\Product;
use App\Rules\BusinessProductRule;
use App\Rules\ProductInventoryRule;
use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
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
            'product_id' => ['required', 'exists:products,id', new BusinessProductRule()],
            'quantity' => [
                'required',
                'integer',
                'min:1', // Ensure quantity is at least 1
                function ($attribute, $value, $fail) {
                    $productId = $this->input("product_id");
                    $quantity = $value;

                    $rule = new ProductInventoryRule($productId, $quantity);
                    if (!$rule->passes($attribute, $value)) {
                        $fail("The product " . Product::find($productId)->product_name . " is out of stock or does not have sufficient quantity!");
                        //$fail($rule->message());
                    }
                },
            ],
        ];
    }
}
