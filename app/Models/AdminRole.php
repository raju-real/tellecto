<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \OwenIt\Auditing\Auditable as Audit;
use OwenIt\Auditing\Contracts\Auditable;

class AdminRole extends Model implements Auditable
{
    use HasFactory, Audit;
    protected $table = "admin_roles";
    protected $guarded = [];

    public function permissions()
    {
        return $this->hasMany(AdminRolePermission::class,'role_id','id');
    }
}
