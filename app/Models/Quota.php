<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quota extends Model
{
    protected $fillable = [
        'lecturer_id',
        'max_supervisees',
        'current_supervisees'
    ];

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }
} 