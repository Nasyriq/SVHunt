@extends('layouts.coordinator')

@section('title', 'Student Management')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Student Management</h2>
        <div class="flex space-x-4">
            <button onclick="document.getElementById('importModal').classList.remove('hidden')"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-file-import mr-2"></i>
                Import Students
            </button>

            <a href="{{ route('coordinator.students.report') }}"
               target="_blank"
               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-print mr-2"></i>
                Generate Report
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
        {{ session('success') }}
    </div>
    @endif

    <!-- Import Modal -->
    <div id="importModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-6 border w-[500px] shadow-lg rounded-md bg-white">
            <div class="import-instructions">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">Import Students</h3>

                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">CSV File Format</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p class="mb-2">Your CSV file should contain the following columns:</p>
                                <div class="bg-white p-3 rounded border border-blue-200 font-mono text-sm">
                                    Matric ID, Name, Email
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h4 class="font-medium text-gray-700 mb-2">Program Detection</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="program-item">
                            <span class="text-xs font-semibold bg-blue-100 text-blue-800 px-2 py-1 rounded">CB</span>
                            <span class="text-sm text-gray-600">Software Engineering</span>
                        </div>
                        <div class="program-item">
                            <span class="text-xs font-semibold bg-green-100 text-green-800 px-2 py-1 rounded">CA</span>
                            <span class="text-sm text-gray-600">Computer System & Networking</span>
                        </div>
                        <div class="program-item">
                            <span class="text-xs font-semibold bg-purple-100 text-purple-800 px-2 py-1 rounded">CD</span>
                            <span class="text-sm text-gray-600">Computer Graphics & Multimedia</span>
                        </div>
                        <div class="program-item">
                            <span class="text-xs font-semibold bg-orange-100 text-orange-800 px-2 py-1 rounded">CF</span>
                            <span class="text-sm text-gray-600">Cybersecurity</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center mb-4">
                    <a href="{{ route('coordinator.students.template') }}"
                       class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                        <i class="fas fa-download mr-2"></i>
                        Download Template
                    </a>
                </div>

                @if(session('importErrors'))
                <div class="mb-4 p-4 bg-red-50 text-red-700 rounded-lg">
                    <p class="font-semibold mb-2">Import Errors:</p>
                    <ul class="list-disc ml-4 text-sm">
                        @foreach(session('importErrors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('coordinator.students.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <input type="file" name="csv_file" accept=".csv" required
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('csv_file')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button"
                                onclick="document.getElementById('importModal').classList.add('hidden')"
                                class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600">
                            Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-6 border w-[500px] shadow-lg rounded-md bg-white">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Edit Student</h3>

            <form id="editForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Matric ID (Read-only) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Matric ID</label>
                    <input type="text" id="edit_matric_id"
                           class="w-full px-3 py-2 border rounded-lg bg-gray-50"
                           readonly>
                </div>

                <!-- Name -->
                <div>
                    <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" id="edit_name" name="name"
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Email -->
                <div>
                    <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="edit_email" name="email"
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Program (Read-only) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Program</label>
                    <input type="text" id="edit_program"
                           class="w-full px-3 py-2 border rounded-lg bg-gray-50"
                           readonly>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button"
                            onclick="document.getElementById('editModal').classList.add('hidden')"
                            class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matric ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($students as $student)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $student->matric_id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $student->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $student->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $student->program }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex space-x-2">
                            <button type="button"
                                    class="edit-student-btn text-blue-500 hover:text-blue-700 transition-colors"
                                    data-student-id="{{ $student->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('coordinator.students.destroy', $student) }}"
                                  method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this student?');"
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4 border-t">
            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add click event listeners to all edit buttons
    document.querySelectorAll('.edit-student-btn').forEach(button => {
        button.addEventListener('click', function() {
            const studentId = this.getAttribute('data-student-id');
            fetch(`/students/${studentId}/edit`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(student => {
                // Populate the form
                document.getElementById('edit_matric_id').value = student.matric_id;
                document.getElementById('edit_name').value = student.name;
                document.getElementById('edit_email').value = student.email;
                document.getElementById('edit_program').value = student.program;

                // Set the form action URL with the correct route
                document.getElementById('editForm').action = `/students/${studentId}/update`;

                // Show the modal
                document.getElementById('editModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to load student data. Please try again.');
            });
        });
    });

    // Close modal when clicking outside
    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });

    // Add form submission handling
    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const action = this.getAttribute('action');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        fetch(action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hide modal and reload page
                document.getElementById('editModal').classList.add('hidden');
                window.location.reload();
            } else {
                alert(data.message || 'Failed to update student');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to update student');
        });
    });
});
</script>
@endsection
