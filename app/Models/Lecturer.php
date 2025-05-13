<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Lecturer extends Authenticatable
{
    protected $fillable = [
        'staff_id',
        'name',
        'email',
        'research_group',
        'password',
        'is_first_login'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_first_login' => 'boolean',
    ];

    public function quota()
    {
        return $this->hasOne(Quota::class);
    }
} 