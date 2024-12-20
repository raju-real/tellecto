<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{
    use HasFactory;

    protected $table = "product_colors";
    protected $appends = ['color_name','color_code'];

    public function getColorNameAttribute()
    {
        return optional(Color::find($this->color_id))->color_name;
    }

    public function getColorCodeAttribute()
    {
        return optional(Color::find($this->color_id))->color_code;
    }
}
