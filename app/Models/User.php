<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

   
    protected $fillable = [
        'employee_code',
        'employee_name',
        'mobile_no',
        'branch_ids',
        'role',
        'max_pics',
        // 'password',
    ];

   
    // protected $hidden = [
    //     'password',
    //     'remember_token',
    // ];
    protected $casts = [
        'branch_ids' => 'array', 
    ];
   
}
