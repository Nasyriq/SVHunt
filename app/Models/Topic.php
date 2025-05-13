<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = [
        'student_id',
        'lecturer_id',
        'title',
        'description',
        'status', // pending, approved, rejected
        'feedback',
        'research_area'
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