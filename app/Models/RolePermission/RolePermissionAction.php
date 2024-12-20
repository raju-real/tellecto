<?php

namespace App\Models\RolePermission;

use App\Traits\SelectedColumn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RolePermissionAction extends Model
{
    use HasFactory, SoftDeletes, SelectedColumn;

    protected $fillable = [
        'role_id',
        'permission_action_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function permission_actions(): BelongsTo
    {
        return $this->belongsTo(PermissionAction::class, 'permission_action_id', 'id')
            ->selectedColumn();
    }
}
