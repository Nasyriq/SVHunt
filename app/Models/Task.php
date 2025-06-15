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
        
        return (int)min(100, round(($elapsedDuration / $totalDuration) * 100));
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

    public function getDurationAttribute()
    {
        $now = now();
        
        if ($now < $this->start_date) {
            $daysUntilStart = (int)round($now->diffInDays($this->start_date));
            if ($daysUntilStart === 0) {
                return 'Starts today';
            } else if ($daysUntilStart === 1) {
                return 'Starts tomorrow';
            } else {
                return $daysUntilStart . ' days until start';
            }
        }

        if ($now > $this->end_date) {
            $daysOverdue = (int)round($now->diffInDays($this->end_date));
            if ($daysOverdue === 0) {
                return 'Ended today';
            } else if ($daysOverdue === 1) {
                return 'Ended yesterday';
            } else {
                return 'Ended ' . $daysOverdue . ' days ago';
            }
        }

        $daysRemaining = (int)round($now->diffInDays($this->end_date));
        if ($daysRemaining === 0) {
            return 'Due today';
        } else if ($daysRemaining === 1) {
            return '1 day left';
        } else {
            return $daysRemaining . ' days left';
        }
    }

    public function getDeadlineStatusAttribute()
    {
        $now = now();
        $daysUntilDeadline = $now->diffInDays($this->end_date, false);
        
        if ($daysUntilDeadline < 0) {
            return [
                'status' => 'overdue',
                'color' => '#dc2626', // red-600
                'text' => 'Overdue'
            ];
        }
        
        if ($daysUntilDeadline <= 7) {
            return [
                'status' => 'critical',
                'color' => '#dc2626', // red-600
                'text' => 'Less than 1 week left'
            ];
        }
        
        if ($daysUntilDeadline <= 30) {
            return [
                'status' => 'warning',
                'color' => '#d97706', // amber-600
                'text' => 'Less than 1 month left'
            ];
        }
        
        return [
            'status' => 'normal',
            'color' => '#059669', // emerald-600
            'text' => 'More than 1 month left'
        ];
    }
} 