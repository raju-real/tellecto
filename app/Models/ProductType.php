<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    use HasFactory;

    protected $table = "product_types";
    protected $appends = ['type_name'];

    public function getTypeNameAttribute()
    {
        return optional(Type::find($this->type_id))->type_name;
    }
}
