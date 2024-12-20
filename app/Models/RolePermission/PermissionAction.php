<?php

namespace App\Models\RolePermission;

use App\Traits\SelectedColumn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissionAction extends Model
{
    use HasFactory, SoftDeletes, SelectedColumn;

    protected $fillable = [
        'permission_id',
        'action_id',
        'path',
        'method',
        'tooltip',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function action(): BelongsTo
    {
        return $this->belongsTo(Action::class, 'action_id', 'id')
            ->selectedColumn();
    }

    public function auth_role_permission_action(): HasMany
    {
        return $this->hasMany(RolePermissionAction::class, 'permission_action_id', 'id')
            ->whereIn('role_id', function ($query1) {
                $query1->select('user_roles.role_id')
//                    ->from((new UserRole())->getTable())
                    ->where('user_roles.user_id', auth()->id())
                    ->whereNull('user_roles.deleted_at');
            })
            ->selectedColumn();
    }
}
