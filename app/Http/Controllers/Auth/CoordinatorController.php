<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Lecturer;

class CoordinatorController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.coordinator.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('coordinator')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/coordinator/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('coordinator')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showDashboard()
    {
        // Get total counts
        $totalStudents = Student::count();
        $totalLecturers = Lecturer::count();

        // Get student distribution data
        $studentDistribution = [
            'Software Engineering' => Student::where('program', 'Software Engineering')->count(),
            'Computer System & Networking' => Student::where('program', 'Computer System & Networking')->count(),
            'Computer Graphics & Multimedia' => Student::where('program', 'Computer Graphics & Multimedia')->count(),
            'Cybersecurity' => Student::where('program', 'Cybersecurity')->count()
        ];

        $researchGroups = [
            [
                'name' => 'CSRG',
                'description' => 'Computer System Research Group',
                'members' => Lecturer::where('research_group', 'CSRG')->count(),
                'color' => '#2193b0'
            ],
            [
                'name' => 'VISIC',
                'description' => 'Virtual Simulation & Computing',
                'members' => Lecturer::where('research_group', 'VISIC')->count(),
                'color' => '#6dd5ed'
            ],
            [
                'name' => 'MIRG',
                'description' => 'Machine Intelligence Research Group',
                'members' => Lecturer::where('research_group', 'MIRG')->count(),
                'color' => '#4CAF50'
            ],
            [
                'name' => 'Cy-SIG',
                'description' => 'Cybersecurity Interest Group',
                'members' => Lecturer::where('research_group', 'Cy-SIG')->count(),
                'color' => '#FF9800'
            ],
            [
                'name' => 'SERG',
                'description' => 'Software Engineering Research Group',
                'members' => Lecturer::where('research_group', 'SERG')->count(),
                'color' => '#E91E63'
            ],
            [
                'name' => 'KECL',
                'description' => 'Knowledge Engineering & Computational Linguistic',
                'members' => Lecturer::where('research_group', 'KECL')->count(),
                'color' => '#9C27B0'
            ],
            [
                'name' => 'DSSim',
                'description' => 'Data Science & Simulation Modeling',
                'members' => Lecturer::where('research_group', 'DSSim')->count(),
                'color' => '#673AB7'
            ],
            [
                'name' => 'DBIS',
                'description' => 'Database Technology & Information System',
                'members' => Lecturer::where('research_group', 'DBIS')->count(),
                'color' => '#3F51B5'
            ],
            [
                'name' => 'EDU-TECH',
                'description' => 'Educational Technology',
                'members' => Lecturer::where('research_group', 'EDU-TECH')->count(),
                'color' => '#00BCD4'
            ],
            [
                'name' => 'ISP',
                'description' => 'Image Signal Processing',
                'members' => Lecturer::where('research_group', 'ISP')->count(),
                'color' => '#009688'
            ],
            [
                'name' => 'CNRG',
                'description' => 'Computer Network Research Group',
                'members' => Lecturer::where('research_group', 'CNRG')->count(),
                'color' => '#FFC107'
            ],
            [
                'name' => 'SCORE',
                'description' => 'Soft Computing & Optimization',
                'members' => Lecturer::where('research_group', 'SCORE')->count(),
                'color' => '#8BC34A'
            ]
        ];

        return view('coordinator.dashboard', compact('totalStudents', 'totalLecturers', 'studentDistribution', 'researchGroups'));
    }
} 