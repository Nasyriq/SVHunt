<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TimeframeController extends Controller
{
    private $colors = [
        '#2193b0',  // Blue
        '#6dd5ed',  // Light Blue
        '#4CAF50',  // Green
        '#FF9800',  // Orange
        '#E91E63',  // Pink
        '#9C27B0',  // Purple
        '#673AB7',  // Deep Purple
        '#3F51B5',  // Indigo
    ];

    public function index()
    {
        $tasks = Task::orderBy('start_date')->get();
        return view('coordinator.timeframe.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'color' => 'required|string|max:7',
            'for_student' => 'boolean',
            'for_lecturer' => 'boolean'
        ]);

        try {
            // Check for overlapping tasks
            $existingTask = Task::where(function($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                      ->orWhereBetween('end_date', [$request->start_date, $request->end_date]);
            })->first();

            if ($existingTask) {
                return back()->with('error', 'Task duration overlaps with existing task: ' . $existingTask->name);
            }

            Task::create($request->all());
            return back()->with('success', 'Task created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create task.');
        }
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'color' => 'required|string|max:7',
            'for_student' => 'boolean',
            'for_lecturer' => 'boolean'
        ]);

        try {
            // Check for overlapping tasks (excluding current task)
            $existingTask = Task::where('id', '!=', $task->id)
                ->where(function($query) use ($request) {
                    $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                          ->orWhereBetween('end_date', [$request->start_date, $request->end_date]);
                })->first();

            if ($existingTask) {
                return back()->with('error', 'Task duration overlaps with existing task: ' . $existingTask->name);
            }

            $task->update($request->all());
            return back()->with('success', 'Task updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update task.');
        }
    }

    public function destroy(Task $task)
    {
        try {
            if ($task->status === 'in-progress') {
                return back()->with('error', 'Cannot delete a task that is in progress.');
            }
            
            $task->delete();
            return back()->with('success', 'Task deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete task.');
        }
    }

    public function edit(Task $task)
    {
        return response()->json([
            'name' => $task->name,
            'description' => $task->description,
            'start_date' => $task->start_date->format('Y-m-d\TH:i'),
            'end_date' => $task->end_date->format('Y-m-d\TH:i'),
            'color' => $task->color,
            'for_student' => $task->for_student,
            'for_lecturer' => $task->for_lecturer
        ]);
    }
} 