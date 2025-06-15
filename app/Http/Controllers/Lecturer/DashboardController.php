<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Topic;
use App\Models\Appointment;
use App\Models\Task;

class DashboardController extends Controller
{
    public function index()
    {
        $lecturer = Auth::guard('lecturer')->user();
        
        // Get pending topics count
        $pendingTopicsCount = Topic::where('lecturer_id', $lecturer->id)
                                 ->where('status', 'pending')
                                 ->count();
        
        // Get pending appointments count
        $pendingAppointmentsCount = Appointment::where('lecturer_id', $lecturer->id)
                                             ->where('status', 'pending')
                                             ->count();
        
        // Get timeframe tasks
        $tasks = Task::where('for_lecturer', true)
                    ->where(function($query) {
                        $query->where('start_date', '<=', now())
                              ->where('end_date', '>=', now());
                    })
                    ->orderBy('start_date')
                    ->get();

        // Get quota information
        $quota = $lecturer->quota;
        $quotaInfo = [
            'current' => $quota ? $quota->current_supervisees : 0,
            'max' => $quota ? $quota->max_supervisees : 0,
            'remaining' => $quota ? ($quota->max_supervisees - $quota->current_supervisees) : 0
        ];
        
        return view('lecturer.dashboard', compact(
            'lecturer',
            'pendingTopicsCount',
            'pendingAppointmentsCount',
            'tasks',
            'quotaInfo'
        ));
    }
} 