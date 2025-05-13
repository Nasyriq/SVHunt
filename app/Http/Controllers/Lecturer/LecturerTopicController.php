<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LecturerTopicController extends Controller
{
    public function index()
    {
        $lecturer = Auth::guard('lecturer')->user();
        $topics = Topic::where('lecturer_id', $lecturer->id)
                      ->with('student')
                      ->latest()
                      ->get();
        
        return view('lecturer.topic.index', compact('topics'));
    }

    public function update(Request $request, Topic $topic)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'feedback' => 'required|string'
        ]);

        $topic->update([
            'status' => $request->status,
            'feedback' => $request->feedback
        ]);

        return back()->with('success', 'Topic ' . $request->status . ' successfully.');
    }

    public function show(Topic $topic)
    {
        $topic->load('student');
        return view('lecturer.topic.show', compact('topic'));
    }
} 