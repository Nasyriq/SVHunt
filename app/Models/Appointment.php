<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'student_id',
        'lecturer_id',
        'title',
        'description',
        'date',
        'time',
        'status', // pending, approved, rejected, completed
        'meeting_link',
        'feedback',
        'location'
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }
} 