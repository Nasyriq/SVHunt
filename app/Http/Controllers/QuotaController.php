<?php

namespace App\Http\Controllers;

use App\Models\Quota;
use App\Models\Lecturer;
use Illuminate\Http\Request;

class QuotaController extends Controller
{
    public function index()
    {
        $quotas = Quota::with('lecturer')->paginate(10);
        $lecturers = Lecturer::doesntHave('quota')->get();
        return view('coordinator.quotas.index', compact('quotas', 'lecturers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lecturer_id' => 'required|exists:lecturers,id|unique:quotas,lecturer_id',
            'max_supervisees' => 'required|integer|min:0|max:20'
        ], [
            'lecturer_id.unique' => 'This lecturer already has a quota assigned.',
            'max_supervisees.max' => 'Maximum supervisees cannot exceed 20.'
        ]);

        try {
            Quota::create([
                'lecturer_id' => $request->lecturer_id,
                'max_supervisees' => $request->max_supervisees,
                'current_supervisees' => 0
            ]);

            return back()->with('success', 'Quota assigned successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to assign quota.');
        }
    }

    public function update(Request $request, Quota $quota)
    {
        $request->validate([
            'max_supervisees' => 'required|integer|min:' . $quota->current_supervisees . '|max:20'
        ], [
            'max_supervisees.min' => 'Maximum supervisees cannot be less than current supervisees.',
            'max_supervisees.max' => 'Maximum supervisees cannot exceed 20.'
        ]);

        try {
            $quota->update([
                'max_supervisees' => $request->max_supervisees
            ]);

            return back()->with('success', 'Quota updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update quota.');
        }
    }

    public function destroy(Quota $quota)
    {
        if ($quota->current_supervisees > 0) {
            return back()->with('error', 'Cannot delete quota while lecturer has active supervisees.');
        }

        try {
            $quota->delete();
            return back()->with('success', 'Quota deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete quota.');
        }
    }
} 