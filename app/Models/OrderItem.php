<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $appends = ['size_name','color_name'];

    public function getSizeNameAttribute()
    {
        return Size::find($this->size_id)->size_name ?? null;
    }

    public function getColorNameAttribute()
    {
        return Color::find($this->color_id)->color_name ?? null;
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public static function getItemVatTypeAgent($product_id)
    {
        return "VAT_FREE";
    }

    public static function getItemVatTypeAdmin($product_id)
    {
        return "VAT_FREE";
    }

    public static function getItemVatTypeBusiness($product_id)
    {
        $product = Product::find($product_id);
        return $product->category->vat_type ?? 'VAT_FREE';
    }

    public static function getItemVatAdmin($product_id, $quantity)
    {
        $vat_type = OrderItem::getItemVatTypeAdmin($product_id);
        $total_vat = 0.00;
        $vat_info['total_vat'] = round($total_vat, 2);
        $vat_info['vat_type'] = $vat_type;
        return $vat_info;

    }

    public static function getItemVatBusiness($product_id, $quantity): array
    {
        $vat_percentage = getVatInfo()->vat_percentage;
        $vat_amount = $vat_percentage / 100;
        $max_vat = getVatInfo()->max_vat;

        $item_price = OrderItem::itemPriceBusiness($product_id, $quantity);
        $vat_type = OrderItem::getItemVatTypeBusiness($product_id);
        $vat_info = [];
        if ($vat_type === "VAT_FREE") {
            $total_vat = 0.00;
            $vat_info['total_vat'] = $total_vat;
            $vat_info['vat_type'] = $vat_type;
        } elseif ($vat_type === "VAT") {
            $total_vat = $item_price * $vat_amount;
            $vat_info['total_vat'] = round($total_vat, 2);
            $vat_info['vat_type'] = $vat_type;
        } elseif ($vat_type === "PARTIAL_VAT") {
            if ($item_price <= $max_vat) {
                $total_vat = $item_price * $vat_amount;
                $vat_info['total_vat'] = round($total_vat, 2);
                $vat_info['vat_type'] = $vat_type;
            } else {
                $vat_info['total_vat'] = 0.00;
                $vat_info['vat_type'] = "VAT_FREE";
            }
        }
        return $vat_info;
    }

    public static function getItemVatAgent($product_id, $quantity): array
    {
        $vat_percentage = getVatInfo()->vat_percentage;
        $vat_amount = $vat_percentage / 100;

        $vat_type = OrderItem::getItemVatTypeAgent($product_id);
        $item_price = OrderItem::itemPriceAgent($product_id, $quantity);
        $total_vat = $item_price * $vat_amount;
        $vat_info = [];
        $vat_info['total_vat'] = round($total_vat, 2);
        $vat_info['vat_type'] = $vat_type;
        return $vat_info;
    }

    public static function itemPriceAgent($product_id, $quantity)
    {
        $condition = [
            'business_id' => authAgentInfo()['business_id'],
            'product_id' => $product_id
        ];
        $business_product = BusinessProductPrice::select('id', 'price', 'sale_price')->where($condition)->first();
        return $business_product->sale_price * $quantity;
    }

    public static function itemPriceBusiness($product_id, $quantity)
    {
        $business_id = authAgentInfo()['business_id'];
        $condition = [
            'business_id' => $business_id,
            'product_id' => $product_id
        ];
        $business_product = BusinessProductPrice::select('id', 'price', 'sale_price')->where($condition)->first();
        return $business_product->price * $quantity;
    }

    public static function itemPriceAdmin($product_id, $quantity)
    {
        $product = Product::select('id', 'price', 'sale_price')->find($product_id);
        return $product->price * $quantity;
    }

    public static function itemSalesAmountAdmin($product_id, $quantity)
    {
        $product = Product::select('id', 'price', 'sale_price')->find($product_id);
        return $product->sale_price * $quantity;
    }

    public static function itemSalesAmountBusiness($product_id, $quantity)
    {
        $business_id = authAgentInfo()['business_id'];
        $condition = [
            'business_id' => $business_id,
            'product_id' => $product_id
        ];
        $business_product = BusinessProductPrice::select('id', 'price', 'sale_price')->where($condition)->first();
        return $business_product->sale_price * $quantity;
    }

    public function scopeAgentSelectedFields($query)
    {
        return $query->select('id','order_id','product_id','business_last_price','quantity','unit','size_id','color_id','item_total_agent','vat_type_agent','total_vat_agent','total_price_agent');
    }

    public function scopeBusinessSelectedFields($query)
    {
        return $query->select('id','order_id','product_id','tellecto_last_price','business_last_price','quantity','unit','size_id','color_id','item_total_business','vat_type_business','total_vat_business','total_price_business','item_total_agent','vat_type_agent','total_vat_agent','total_price_agent');
    }

    public function scopeAdminSelectedFields($query)
    {
        return $query->select('*');
    }
}
