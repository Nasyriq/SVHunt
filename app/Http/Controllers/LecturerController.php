<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lecturer;
use PDF;

class LecturerController extends Controller
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
        $lecturers = Lecturer::paginate(10);
        return view('coordinator.lecturers.index', compact('lecturers'));
    }

    public function importCSV(Request $request)
    {
        try {
            $request->validate([
                'csv_file' => 'required|mimes:csv,txt|max:2048'
            ], [
                'csv_file.required' => 'Please select a CSV file to import.',
                'csv_file.mimes' => 'The file must be a CSV file.',
                'csv_file.max' => 'The file size must not exceed 2MB.',
            ]);

            $file = $request->file('csv_file');
            
            if (!$file->isValid()) {
                return back()->with('error', 'Invalid file upload. Please try again.');
            }

            $path = $file->getRealPath();
            $records = array_map('str_getcsv', file($path));

            // Check if file is empty
            if (count($records) <= 1) { // 1 because of header row
                return back()->with('error', 'The CSV file is empty. Please add some records.');
            }

            // Remove header row
            array_shift($records);

            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            foreach ($records as $index => $record) {
                $lineNumber = $index + 2; // +2 because of 0-based index and header row
                
                // Check if record has all required fields
                if (count($record) < 4) {
                    $errors[] = "Line {$lineNumber}: Missing required fields. Expected: Staff ID, Name, Email, Research Group";
                    $errorCount++;
                    continue;
                }

                $staffId = trim($record[0]);
                $name = trim($record[1]);
                $email = trim($record[2]);
                $researchGroup = trim($record[3]);

                // Validate staff ID format
                if (!preg_match('/^FK\d{5}$/i', $staffId)) {
                    $errors[] = "Line {$lineNumber}: Invalid staff ID format for '{$staffId}'. Format should be FK12345";
                    $errorCount++;
                    continue;
                }

                // Validate email format
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Line {$lineNumber}: Invalid email format for '{$email}'";
                    $errorCount++;
                    continue;
                }

                // Check for duplicate staff ID
                if (Lecturer::where('staff_id', $staffId)->exists()) {
                    $errors[] = "Line {$lineNumber}: Staff ID '{$staffId}' already exists in the database";
                    $errorCount++;
                    continue;
                }

                // Validate research group
                if (!array_key_exists($researchGroup, $this->researchGroups)) {
                    $errors[] = "Line {$lineNumber}: Invalid research group '{$researchGroup}'. Must be one of: " . implode(', ', array_keys($this->researchGroups));
                    $errorCount++;
                    continue;
                }

                try {
                    Lecturer::create([
                        'staff_id' => $staffId,
                        'name' => $name,
                        'email' => $email,
                        'research_group' => $researchGroup
                    ]);
                    $successCount++;
                } catch (\Exception $e) {
                    $errors[] = "Line {$lineNumber}: Failed to import record - " . $e->getMessage();
                    $errorCount++;
                }
            }

            // Prepare response message
            if ($errorCount > 0) {
                $message = "Import completed with errors. Successfully imported {$successCount} records. Failed to import {$errorCount} records.";
                return back()
                    ->with('warning', $message)
                    ->with('importErrors', $errors);
            }

            if ($successCount === 0) {
                return back()->with('error', 'No records were imported. Please check your CSV file format.');
            }

            return back()->with('success', "Successfully imported {$successCount} records.");

        } catch (\Exception $e) {
            \Log::error('CSV Import Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to process the CSV file: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=lecturer_template.csv',
        ];

        $template = "Staff ID,Name,Email,Research Group\n";
        $template .= "FK12001,Dr. John Doe,john@example.com,CSRG\n";
        $template .= "FK12002,Dr. Jane Smith,jane@example.com,VISIC\n";
        $template .= "FK12003,Dr. Alice Brown,alice@example.com,MIRG\n";
        $template .= "FK12004,Dr. Bob Wilson,bob@example.com,Cy-SIG\n";
        
        return response($template, 200, $headers);
    }

    public function edit(Lecturer $lecturer)
    {
        return response()->json($lecturer);
    }

    public function update(Request $request, Lecturer $lecturer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:lecturers,email,' . $lecturer->id,
            'research_group' => 'required|string|in:' . implode(',', array_keys($this->researchGroups))
        ]);

        try {
            $lecturer->update([
                'name' => $request->name,
                'email' => $request->email,
                'research_group' => $request->research_group
            ]);
            
            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }
            return back()->with('success', 'Lecturer updated successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to update lecturer.']);
            }
            return back()->with('error', 'Failed to update lecturer.');
        }
    }

    public function destroy(Lecturer $lecturer)
    {
        try {
            $lecturer->delete();
            return back()->with('success', 'Lecturer deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete lecturer.');
        }
    }

    public function generateReport()
    {
        $lecturers = Lecturer::all();
        return view('coordinator.lecturers.report', compact('lecturers'));
    }
} 