<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Lecturer;

class LecturerAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.lecturer-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'staff_id' => 'required',
            'password' => 'required'
        ]);

        if (Auth::guard('lecturer')->attempt($credentials)) {
            $lecturer = Auth::guard('lecturer')->user();
            
            if ($lecturer->is_first_login) {
                return redirect()->route('lecturer.change-password');
            }

            return redirect()->route('lecturer.dashboard');
        }

        return back()->withErrors([
            'staff_id' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showChangePasswordForm()
    {
        return view('auth.change-password', ['type' => 'lecturer']);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed'
        ]);

        $lecturer = Auth::guard('lecturer')->user();

        if (!Hash::check($request->current_password, $lecturer->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $lecturer->password = Hash::make($request->password);
        $lecturer->is_first_login = false;
        $lecturer->save();

        return redirect()->route('lecturer.dashboard')->with('success', 'Password changed successfully');
    }

    public function logout(Request $request)
    {
        Auth::guard('lecturer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

} 