<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'employee_code',
        'employee_name',
        'client',
        'state',
        'branch',
        'mobile_no',
        'password',
    ];

    protected $casts = [
        'branch' => 'array',
    ];
}
