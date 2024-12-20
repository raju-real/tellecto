<?php

namespace App\Rules;

use App\Models\DeliveryCharge;
use Illuminate\Contracts\Validation\Rule;

class ParcelShopRequired implements Rule
{
    protected $delivery_id;

    public function __construct($delivery_id)
    {
        $this->delivery_id = $delivery_id;
    }

    public function passes($attribute, $value)
    {
        $delivery_method = DeliveryCharge::find($this->delivery_id);

        if($delivery_method->parcel_shop_status === 0) {
            return true;
        }
        if (!$delivery_method) {
            return false;
        }
        if ($delivery_method->parcel_shop_status === 1) {
            if (!isset($value['service_point_id'], $value['shop_name'], $value['city'], $value['street_name'], $value['street_number'], $value['postal_code'])) {
                return false; // Fail validation if required fields are missing
            }
        }
        return true;
    }

    public function message()
    {
        $delivery_method = DeliveryCharge::find($this->delivery_id);
        return "The :attribute service point,shop name, street name, street number, postal code fields are required when the delivery method is {$delivery_method->delivery_type}.";
    }
}
