<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroBanner extends Model
{
    use HasFactory;
    protected $table = "hero_banners";

    public function getImageAttribute($value)
    {
        return $value != NULL ? url('/') . '/' . $value : NULL;
    }
}
