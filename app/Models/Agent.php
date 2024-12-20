<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use \OwenIt\Auditing\Auditable as Audit;
use OwenIt\Auditing\Contracts\Auditable;

class Agent extends Authenticatable implements Auditable
{
    use HasApiTokens, HasFactory, Notifiable, Audit;

    protected $table = "agents";
    protected string $guard = 'agent';
    protected $fillable = ['email', 'password', 'business_id', 'role_id', 'agent_code', 'personal_id', 'manager_name'
        , 'first_name', 'last_name', 'phone', 'email', 'zip_code'];
    protected $appends = ['full_name'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function business()
    {
        return $this->belongsTo(User::class,'business_id','id');
    }

    public function getFullNameAttribute() {
        return $this->first_name. ' '.$this->last_name;
    }

    public static function getAgentFullName($agent_id) {
        $agent = Agent::find($agent_id);
        return $agent->first_name. ' '.$agent->last_name;
    }
}
