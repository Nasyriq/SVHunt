<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LecturerProfileController extends Controller
{
    public function index()
    {
        $lecturer = Auth::guard('lecturer')->user();
        return view('lecturer.profile.index', compact('lecturer'));
    }

    public function update(Request $request)
    {
        $lecturer = Auth::guard('lecturer')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:lecturers,email,' . $lecturer->id,
            'expertise' => 'required|string|max:255',
            'current_password' => 'required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        // Check current password if trying to change password
        if ($request->current_password) {
            if (!Hash::check($request->current_password, $lecturer->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect']);
            }
            $lecturer->password = Hash::make($request->new_password);
        }

        $lecturer->name = $request->name;
        $lecturer->email = $request->email;
        $lecturer->expertise = $request->expertise;
        $lecturer->save();

        return back()->with('success', 'Profile updated successfully.');
    }
} 