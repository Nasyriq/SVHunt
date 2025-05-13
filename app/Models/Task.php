<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'color',
        'for_student',
        'for_lecturer'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'for_student' => 'boolean',
        'for_lecturer' => 'boolean'
    ];

    public function getProgressPercentageAttribute()
    {
        $now = now();
        
        if ($now < $this->start_date) {
            return 0;
        }
        
        if ($now > $this->end_date) {
            return 100;
        }
        
        $totalDuration = $this->end_date->diffInSeconds($this->start_date);
        $elapsedDuration = $now->diffInSeconds($this->start_date);
        
        return min(100, round(($elapsedDuration / $totalDuration) * 100));
    }

    public function getStatusAttribute($value)
    {
        if ($this->end_date < now()) {
            return 'completed';
        }
        if ($this->start_date > now()) {
            return 'upcoming';
        }
        return 'in-progress';
    }
} 