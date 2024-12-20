<?php

namespace App\Models\RolePermission;

use App\Traits\SelectedColumn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Action extends Model
{
    use HasFactory, SoftDeletes, SelectedColumn;

    protected $fillable = [
        'name',
        'status'
    ];
}
