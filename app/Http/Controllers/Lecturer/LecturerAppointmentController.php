<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LecturerAppointmentController extends Controller
{
    public function index()
    {
        $lecturer = Auth::guard('lecturer')->user();
        $appointments = Appointment::where('lecturer_id', $lecturer->id)
                                 ->with('student')
                                 ->latest()
                                 ->get();
        
        return view('lecturer.appointment.index', compact('appointments'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,completed',
            'feedback' => 'nullable|string',
            'meeting_link' => 'nullable|url|required_if:status,approved'
        ]);

        $appointment->update([
            'status' => $request->status,
            'feedback' => $request->feedback,
            'meeting_link' => $request->meeting_link
        ]);

        return back()->with('success', 'Appointment ' . $request->status . ' successfully.');
    }
} 