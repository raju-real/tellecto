<?php

namespace App\Rules;

use App\Models\DeliveryCharge;
use App\Models\Product;
use Illuminate\Contracts\Validation\Rule;

class DeliveryWeightLimitRule implements Rule
{
    protected $items;
    protected $totalWeight;

    public function __construct($items)
    {
        $this->items = $items;
        $this->totalWeight = 0;
    }

    public function passes($attribute, $value)
    {
        foreach ($this->items as $item) {
            $product = Product::find($item['id']);
            $quantity = $item['quantity'];
            $weight = $item['weight'] ?? $product->weight;
            $this->totalWeight += ($weight * $quantity);
        }

        $deliveryMethod = DeliveryCharge::find($value);
        if ($deliveryMethod) {
            $weightLimit = (float) $deliveryMethod->max_weight;
            $totalWeight = (float) $this->totalWeight;

            if ($totalWeight > $weightLimit) {
                return false;
            }
        }
        return true;
    }

    public function message()
    {
        return "The selected delivery method does not allow shipments over the weight limit.";
    }
}
