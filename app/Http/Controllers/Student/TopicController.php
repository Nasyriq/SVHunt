<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicController extends Controller
{
    private $researchGroups = [
        'CSRG' => 'Computer System Research Group',
        'VISIC' => 'Virtual Simulation & Computing',
        'MIRG' => 'Machine Intelligence Research Group',
        'Cy-SIG' => 'Cybersecurity Interest Group',
        'SERG' => 'Software Engineering Research Group',
        'KECL' => 'Knowledge Engineering & Computational Linguistic',
        'DSSim' => 'Data Science & Simulation Modeling',
        'DBIS' => 'Database Technology & Information System',
        'EDU-TECH' => 'Educational Technology',
        'ISP' => 'Image Signal Processing',
        'CNRG' => 'Computer Network Research Group',
        'SCORE' => 'Soft Computing & Optimization'
    ];

    public function index()
    {
        $topics = Topic::where('student_id', Auth::guard('student')->id())
                      ->with('lecturer')
            ->get()
            ->sortBy([
                // Pending first, then approved/rejected
                fn($a, $b) => $a->status === 'pending' && $b->status !== 'pending' ? -1 : ($a->status !== 'pending' && $b->status === 'pending' ? 1 : 0),
                // For pending, latest first
                fn($a, $b) => $a->status === 'pending' && $b->status === 'pending'
                    ? $b->created_at <=> $a->created_at
                    : 0,
                // For non-pending, oldest first
                fn($a, $b) => $a->status !== 'pending' && $b->status !== 'pending'
                    ? $a->created_at <=> $b->created_at
                    : 0,
            ])
            ->values();
        
        $lecturers = Lecturer::all();
        $researchGroups = $this->researchGroups;
        
        return view('student.topic.index', compact('topics', 'lecturers', 'researchGroups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'lecturer_id' => 'required|exists:lecturers,id',
            'research_group' => 'required|string|in:' . implode(',', array_keys($this->researchGroups))
        ]);

        // Verify that the selected lecturer belongs to the selected research group
        $lecturer = Lecturer::findOrFail($request->lecturer_id);
        if ($lecturer->research_group !== $request->research_group) {
            return back()->with('error', 'The selected supervisor does not belong to the selected research group.');
        }

        $topic = Topic::create([
            'student_id' => Auth::guard('student')->id(),
            'lecturer_id' => $request->lecturer_id,
            'title' => $request->title,
            'description' => $request->description,
            'research_area' => $request->research_group,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Topic application submitted successfully.');
    }

    public function edit(Topic $topic)
    {
        // Check if the topic belongs to the authenticated student
        if ($topic->student_id !== Auth::guard('student')->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if the topic is still pending
        if ($topic->status !== 'pending') {
            return response()->json(['error' => 'Cannot edit topic that has been ' . $topic->status], 400);
        }

        return response()->json([
            'title' => $topic->title,
            'description' => $topic->description,
            'research_area' => $topic->research_area,
            'lecturer_id' => $topic->lecturer_id
        ]);
    }

    public function update(Request $request, Topic $topic)
    {
        if ($topic->status !== 'pending') {
            return back()->with('error', 'Cannot update topic that has been ' . $topic->status);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'research_area' => 'required|string|max:255',
            'lecturer_id' => 'required|exists:lecturers,id'
        ]);

        $topic->update($request->all());

        return back()->with('success', 'Topic updated successfully.');
    }

    public function destroy(Topic $topic)
    {
        if ($topic->status !== 'pending') {
            return back()->with('error', 'Cannot delete topic that has been ' . $topic->status);
        }

        $topic->delete();
        return back()->with('success', 'Topic deleted successfully.');
    }
} 