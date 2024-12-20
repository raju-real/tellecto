<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use \OwenIt\Auditing\Auditable as Audit;
use OwenIt\Auditing\Contracts\Auditable;


class Admin extends Authenticatable implements Auditable
{
    use HasApiTokens, HasFactory, Notifiable, Audit;
    protected $table = "admins";
    protected string $guard = 'admin';
    protected $fillable = ['name', 'type', 'role_id', 'email', 'mobile', 'password', 'image'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
