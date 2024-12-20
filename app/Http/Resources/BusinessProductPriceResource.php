<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BusinessProductPriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_number' => $this->product_number,
            'previous_price' => $this->when(auth()->guard('business')->check(), $this->previous_price),
            'price' => $this->when(auth()->guard('business')->check(), $this->price),
            'profit_type' => $this->profit_type,
            'profit' => $this->profit,
            'sale_price' => $this->sale_price,
            'profit_amount' => $this->profit_amount,
            'active_status' => $this->active_status,
            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}

