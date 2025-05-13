<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use PDF;

class StudentController extends Controller
{
    // Program mapping based on matric ID prefix
    private $programMapping = [
        'CB' => 'Software Engineering',
        'CA' => 'Computer System & Networking',
        'CD' => 'Computer Graphics & Multimedia',
        'CF' => 'Cybersecurity'
    ];

    public function index()
    {
        $students = Student::paginate(10);
        return view('coordinator.students.index', compact('students'));
    }

    public function importCSV(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048'
        ], [
            'csv_file.required' => 'Please select a CSV file to import.',
            'csv_file.mimes' => 'The file must be a CSV file.',
        ]);

        try {
            $file = $request->file('csv_file');
            $path = $file->getRealPath();
            $records = array_map('str_getcsv', file($path));

            // Remove header row
            array_shift($records);

            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            foreach ($records as $index => $record) {
                $lineNumber = $index + 2; // +2 because of 0-based index and header row
                
                // Check if record has required fields
                if (count($record) < 3) {
                    $errors[] = "Line {$lineNumber}: Invalid record format";
                    $errorCount++;
                    continue;
                }

                $matricId = trim($record[0]);
                $name = trim($record[1]);
                $email = trim($record[2]);

                // Validate matric ID format
                if (!preg_match('/^(CB|CA|CD|CF)\d{5}$/i', $matricId)) {
                    $errors[] = "Line {$lineNumber}: Invalid matric ID format for '{$matricId}'";
                    $errorCount++;
                    continue;
                }

                // Check for duplicate matric ID
                if (Student::where('matric_id', $matricId)->exists()) {
                    $errors[] = "Line {$lineNumber}: Duplicate matric ID '{$matricId}'";
                    $errorCount++;
                    continue;
                }

                // Get program from matric ID prefix
                $prefix = strtoupper(substr($matricId, 0, 2));
                $program = $this->programMapping[$prefix] ?? null;

                if (!$program) {
                    $errors[] = "Line {$lineNumber}: Invalid program prefix in matric ID '{$matricId}'";
                    $errorCount++;
                    continue;
                }

                try {
                    Student::create([
                        'matric_id' => $matricId,
                        'name' => $name,
                        'email' => $email,
                        'program' => $program
                    ]);
                    $successCount++;
                } catch (\Exception $e) {
                    $errors[] = "Line {$lineNumber}: Failed to import record - {$e->getMessage()}";
                    $errorCount++;
                }
            }

            $message = "Import completed. Successfully imported {$successCount} records.";
            if ($errorCount > 0) {
                $message .= " Failed to import {$errorCount} records.";
                return back()->with('warning', $message)->with('importErrors', $errors);
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to process the CSV file: ' . $e->getMessage());
        }
    }

    public function generateReport()
    {
        $students = Student::all();
        return view('coordinator.students.report', compact('students'));
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=student_template.csv',
        ];

        $template = "Matric ID,Name,Email\n";
        $template .= "CB20001,John Doe,john@example.com\n";
        $template .= "CA20002,Jane Smith,jane@example.com\n";
        $template .= "CD20003,Alice Johnson,alice@example.com\n";
        $template .= "CF20004,Bob Wilson,bob@example.com\n";
        
        return response($template, 200, $headers);
    }

    public function destroy(Student $student)
    {
        try {
            $student->delete();
            return back()->with('success', 'Student deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete student.');
        }
    }

    public function edit(Student $student)
    {
        return response()->json($student);
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
        ]);

        try {
            $student->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
            
            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }
            return back()->with('success', 'Student updated successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to update student.']);
            }
            return back()->with('error', 'Failed to update student.');
        }
    }
} 