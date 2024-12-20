<?php

namespace App\Models\RolePermission;

use App\Models\User;
use App\Traits\SelectedColumn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes, SelectedColumn;

    protected $fillable = [
        'name',
        'type',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function users()
    {
        return $this->hasMany(User::class,'role_id','ie');
    }

    public function role_permission_actions(): HasMany
    {
        return $this->hasMany(RolePermissionAction::class, 'role_id', 'id')
            ->selectedColumn();
    }
}
