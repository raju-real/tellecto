<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderParcelShop extends Model
{
    use HasFactory;
    protected $table = 'order_parcel_shops';

    public function order() {
        return $this->belongsTo(Order::class,'order_id','id');
    }
}
