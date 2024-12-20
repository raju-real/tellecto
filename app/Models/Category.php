<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use OwenIt\Auditing\Auditable as Audit;
use OwenIt\Auditing\Contracts\Auditable;


class Category extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Audit;

    protected $table = "categories";

    public function products()
    {
        return $this->hasMany(Product::class,'category_id','id');
    }

    public function subcategories()
    {
        return $this->hasMany(SubCategory::class, 'category_id', 'id');
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
