<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Product;

class ProductInventoryRule implements Rule
{
    protected $productId;
    protected $quantity;

    public function __construct($productId, $quantity)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    public function passes($attribute, $value)
    {
        $product = Product::find($this->productId);

        // Check if the product exists, has stock status 'YES' and enough inventory
        return $product && $product->stock_status === 'YES' && $product->inventory >= $this->quantity;
    }

    public function message()
    {
        return 'The selected product does not have sufficient inventory or is out of stock.';
    }
}

