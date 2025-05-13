<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;
use App\Models\Task;

class StudentAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.student-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'matric_id' => 'required',
            'password' => 'required'
        ]);

        if (Auth::guard('student')->attempt($credentials)) {
            $student = Auth::guard('student')->user();
            
            if ($student->is_first_login) {
                return redirect()->route('student.change-password');
            }

            return redirect()->route('student.dashboard');
        }

        return back()->withErrors([
            'matric_id' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showChangePasswordForm()
    {
        return view('auth.change-password', ['type' => 'student']);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed'
        ]);

        $student = Auth::guard('student')->user();

        if (!Hash::check($request->current_password, $student->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $student->password = Hash::make($request->password);
        $student->is_first_login = false;
        $student->save();

        return redirect()->route('student.dashboard')->with('success', 'Password changed successfully');
    }

    public function logout()
    {
        Auth::guard('student')->logout();
        return redirect()->route('student.login');
    }

    public function dashboard()
    {
        $student = Auth::guard('student')->user();
        $tasks = Task::where('for_student', true)
                     ->where(function($query) {
                         $query->where('start_date', '<=', now())
                               ->where('end_date', '>=', now());
                     })
                     ->orderBy('start_date')
                     ->get();

        // You'll need to implement these counts based on your Topic and Appointment models
        $pendingTopicCount = 0; // Replace with actual count
        $pendingAppointmentCount = 0; // Replace with actual count

        return view('student.dashboard', compact('tasks', 'pendingTopicCount', 'pendingAppointmentCount'));
    }
} 