<?php

namespace App\Models\RolePermission;

use App\Traits\SelectedColumn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Permission extends Model
{
    use HasFactory, SoftDeletes, SelectedColumn, HasRelationships;

    protected $fillable = [
        'name',
        'order_no',
        'path',
        'path_group',
        'backend_path',
        'icon',
        'parent_id',
        'type',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function all_child_permission(): HasMany
    {
        return $this->child_permission()
            ->with('child_permission')
            ->whereHas('child_permission');
    }

    public function child_permission(): HasMany
    {
        return $this->hasMany(Permission::class, 'parent_id', 'id')
            ->where('status', true)
            ->orderBy('order_no', 'ASC')
            ->with('permission_action.action')
            ->whereHas('permission_action')
            ->selectedColumn();
    }

    public function all_child(): HasMany
    {
        return $this->child()->with('child');
    }

    public function child(): HasMany
    {
        return $this->hasMany(Permission::class, 'parent_id', 'id')
            ->orderBy('order_no', 'ASC')
            ->when(request('size') == -1, function ($query) {
                $query->with('permission_action.action');
            })
            ->selectedColumn();
    }

    public function auth_all_child_side_bar(): HasMany
    {
        return $this->auth_child_side_bar()
            ->with([
                'auth_child_side_bar',
                'auth_child_tab'
            ]);
    }

    public function auth_child_side_bar(): HasMany
    {
        return $this->hasMany(Permission::class, 'parent_id', 'id')
            ->orderBy('order_no', 'ASC')
            ->where('type', 'Side Bar')
            ->with([
                'permission_action' => function ($query1) {
                    $query1->when(request('user_type') != 'Super Admin',
                        function ($query2) {
                            $query2->whereHas('auth_role_permission_action');
                        })
                        ->with('action');
                }])
            ->when(request('user_type') != 'Super Admin',
                function ($query1) {
                    $query1->when(request('db_auth_permission_ids'),
                        function ($query2) {
                            $query2->whereIn('id', request('db_auth_permission_ids'));
                        });
                })
            ->selectedColumn();
    }

    public function auth_all_child_tab(): HasMany
    {
        return $this->auth_child_tab()
            ->with([
                'auth_child_side_bar',
                'auth_child_tab'
            ]);
    }

    public function auth_child_tab(): HasMany
    {
        return $this->hasMany(Permission::class, 'parent_id', 'id')
            ->orderBy('order_no', 'ASC')
            ->where('type', 'Tab')
            ->with([
                'permission_action' => function ($query1) {
                    $query1->when(request('user_type') != 'Super Admin',
                        function ($query2) {
                            $query2->whereHas('auth_role_permission_action');
                        })
                        ->with('action');
                }])
            ->when(request('user_type') != 'Super Admin',
                function ($query1) {
                    $query1->when(request('db_auth_permission_ids'),
                        function ($query2) {
                            $query2->whereIn('id', request('db_auth_permission_ids'));
                        });
                })
            ->selectedColumn();
    }

    public function permission_action(): HasMany
    {
        return $this->hasMany(PermissionAction::class, 'permission_id', 'id')
            ->selectedColumn();
    }

    public function parent()
    {
        return $this->belongsTo(Permission::class, 'parent_id', 'id')
            ->selectedColumn();
    }

    public function actions(): HasManyDeep
    {
        return $this->hasManyDeep(
            Action::class,
            [PermissionAction::class],
            ['permission_id', 'id'],
            ['id', 'action_id']
        )
            ->selectedColumn();
    }
}
