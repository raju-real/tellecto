<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "addresses";

    public function getThumbnailAttribute($value)
    {
        return $value != NULL ? url('/') . '/' . $value : NULL;

    }

    public function getOriginalAttribute($value)
    {
        return $value != NULL ? url('/') . '/' . $value : NULL;
    }
}
