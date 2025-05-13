<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    protected $fillable = [
        'matric_id',
        'name',
        'email',
        'program',
        'password',
        'is_first_login'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_first_login' => 'boolean',
    ];
} 