<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    use HasFactory;

    protected $table = "product_sizes";
    protected $appends = ['size_name'];

    public function getSizeNameAttribute()
    {
        return optional(Size::find($this->size_id))->size_name;
    }
}
