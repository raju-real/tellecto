<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class AlgoliaProductView extends Model
{
    use HasFactory;

    public function getImageAttribute($value)
    {
        return $value == null ? null : url('/') . '/' . $value;
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id', 'id');
    }

    public function getCategoriesAttribute()
    {
        return $this->category->name . ' > ' . $this->subcategory->name;
    }
}
