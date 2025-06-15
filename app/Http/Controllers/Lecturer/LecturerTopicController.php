<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Quota;

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

        $oldStatus = $topic->status;

        $topic->update([
            'status' => $request->status,
            'feedback' => $request->feedback
        ]);

        if ($request->status === 'approved' && $oldStatus !== 'approved') {
            $quota = Quota::where('lecturer_id', $topic->lecturer_id)->first();
            if ($quota) {
                $quota->increment('current_supervisees');
            }
        }

        if ($request->status === 'rejected' && $oldStatus === 'approved') {
            $quota = Quota::where('lecturer_id', $topic->lecturer_id)->first();
            if ($quota && $quota->current_supervisees > 0) {
                $quota->decrement('current_supervisees');
            }
        }

        return back()->with('success', 'Topic ' . $request->status . ' successfully.');
    }

    public function show(Topic $topic)
    {
        $topic->load('student');
        return view('lecturer.topic.show', compact('topic'));
    }
} 