<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\RolePermission\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use \OwenIt\Auditing\Auditable as Audit;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements Auditable
{
    use HasApiTokens, HasFactory, Notifiable, Audit;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'mobile',
        'email',
        'password',
        'role_id',
        'is_active',
        'responsible_by',
        'user_status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getImageAttribute($value)
    {
        return url('/') . '/' . $value;
    }

    public function role_info()
    {
        return $this->belongsTo(Role::class, 'role_id')->select('id', 'name','type', 'status');
    }

    public function user_information()
    {
        return $this->hasOne(UserInformation::class, 'user_id', 'id');
    }


//    public function getRoleInfoAttribute()
//    {
//        $role = Role::select('id','name','type','status')->find($this->role_id);
//        if (isset($role)) {
//            return $role;
//        } else {
//            return null;
//        }
//    }
    public function scopeActive($query)
    {
        $query->where('is_active', 1);
    }

    public function scopeAccept($query)
    {
        $query->where('user_status', 'accept');
    }

    public function scopeSelectedFields($query)
    {
        $query->select('id', 'role_id', 'name', 'email', 'mobile', 'username', 'image', 'is_active','user_status');
    }

    public function scopeAdmin($query)
    {
        $query->whereHas('role_info', function (Builder $role) {
            $role->where('type', 'ADMIN');
        });
    }

    public function scopeBusiness($query)
    {
        $query->whereHas('role_info', function (Builder $role) {
            $role->where('type', 'BUSINESS');
        });
    }
}
