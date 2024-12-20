<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class SubCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "sub_categories";

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function products()
    {
        return $this->hasMany(Product::class,'subcategory_id','id');
    }

    public function getThumbnailAttribute($value)
    {
        return $value != NULL ? url('/') . '/' . $value : NULL;

    }

    public function getOriginalAttribute($value)
    {
        return $value != NULL ? url('/') . '/' . $value : NULL;
    }

    public function scopeActive($query)
    {
        return $query->where('active_status',1);
    }
}
