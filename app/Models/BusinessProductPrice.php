<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessProductPrice extends Model
{
    use HasFactory;
    protected $table = "business_product_prices";
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }

    public static function calculateProfit($price, $profitType, $profit)
    {
        $salePrice = $price;
        $profitAmount = 0.00;

        if ($profitType === 'FLAT') {
            $salePrice = $price + $profit;
            $profitAmount = $profit;
        } elseif ($profitType === 'PERCENTAGE') {
            $profitAmount = ($price * $profit) / 100;
            $salePrice = $price + $profitAmount;
        }

        return [
            'sale_price' => $salePrice,
            'profit_amount' => $profitAmount
        ];
    }
}
