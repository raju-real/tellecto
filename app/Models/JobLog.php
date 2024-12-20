<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'job_name',
        'started_at',
        'ended_at',
        'status',
        'error_message'
    ];

    public $timestamps = true;
}
