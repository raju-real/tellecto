<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \OwenIt\Auditing\Auditable as Audit;
use OwenIt\Auditing\Contracts\Auditable;

class AdminRolePermission extends Model implements Auditable
{
    use HasFactory, Audit;
    protected $table = "admin_role_permissions";
    protected $guarded = [];
    protected $appends = ['menu_name','activity_name'];

    public function getMenuNameAttribute()
    {
        return AdminMenu::find($this->menu_id)->name ?? Null;
    }
    public function getActivityNameAttribute()
    {
        return AdminMenuActivity::find($this->activity_id)->activity_name ?? Null;
    }
}
