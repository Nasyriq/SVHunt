@extends('layouts.coordinator')

@section('title', 'Timeframe Management')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Timeframe Management</h2>
        <button onclick="document.getElementById('addTaskModal').classList.remove('hidden')" 
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Add New Task
        </button>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
        {{ session('error') }}
    </div>
    @endif

    <!-- Progress Chart -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Progress Timeline</h3>
        <div class="space-y-6">
            @foreach($tasks as $task)
            <div class="task-item">
                <div class="flex justify-between items-center mb-2">
                    <div>
                        <h4 class="font-medium">{{ $task->name }}</h4>
                        <p class="text-sm text-gray-600">
                            {{ $task->start_date->format('d M Y, H:i') }} - 
                            {{ $task->end_date->format('d M Y, H:i') }}
                        </p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium">{{ $task->progress_percentage }}%</span>
                        <div class="flex space-x-2">
                            <button onclick="editTask({{ $task->id }})" 
                                    class="text-blue-500 hover:text-blue-700 transition-colors">
                                <i class="fas fa-edit"></i>
                            </button>
                            @if($task->status !== 'in-progress')
                            <form action="{{ route('coordinator.timeframe.destroy', $task) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this task?');"
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="relative pt-1">
                    <div class="flex mb-2 items-center justify-between">
                        <div>
                            <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full"
                                  style="background-color: {{ $task->color }}20; color: {{ $task->color }}">
                                {{ $task->status }}
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-semibold inline-block">
                                For: 
                                @if($task->for_student && $task->for_lecturer)
                                    All
                                @elseif($task->for_student)
                                    Students
                                @else
                                    Lecturers
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200">
                        <div style="width: {{ $task->progress_percentage }}%; background-color: {{ $task->color }}" 
                             class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center transition-all duration-500">
                        </div>
                    </div>
                </div>
                @if($task->description)
                <p class="text-sm text-gray-600 mt-2">{{ $task->description }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <!-- Add Task Modal -->
    <div id="addTaskModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-6 border w-[500px] shadow-lg rounded-md bg-white">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Add New Task</h3>
            
            <form action="{{ route('coordinator.timeframe.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Task Name</label>
                    <input type="text" name="name" required
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="datetime-local" name="start_date" required
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="datetime-local" name="end_date" required
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                    <select name="color" required
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="#2193b0">Blue</option>
                        <option value="#6dd5ed">Light Blue</option>
                        <option value="#4CAF50">Green</option>
                        <option value="#FF9800">Orange</option>
                        <option value="#E91E63">Pink</option>
                        <option value="#9C27B0">Purple</option>
                        <option value="#673AB7">Deep Purple</option>
                        <option value="#3F51B5">Indigo</option>
                    </select>
                </div>

                <div class="flex space-x-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="for_student" value="1" checked
                               class="rounded border-gray-300 text-blue-500 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600">For Students</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="for_lecturer" value="1" checked
                               class="rounded border-gray-300 text-blue-500 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600">For Lecturers</span>
                    </label>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="document.getElementById('addTaskModal').classList.add('hidden')"
                            class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600">
                        Add Task
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div id="editTaskModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-6 border w-[500px] shadow-lg rounded-md bg-white">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Edit Task</h3>
            
            <form id="editTaskForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Task Name</label>
                    <input type="text" name="name" id="editName" required
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="editDescription" rows="3"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="datetime-local" name="start_date" id="editStartDate" required
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="datetime-local" name="end_date" id="editEndDate" required
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                    <select name="color" id="editColor" required
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="#2193b0">Blue</option>
                        <option value="#6dd5ed">Light Blue</option>
                        <option value="#4CAF50">Green</option>
                        <option value="#FF9800">Orange</option>
                        <option value="#E91E63">Pink</option>
                        <option value="#9C27B0">Purple</option>
                        <option value="#673AB7">Deep Purple</option>
                        <option value="#3F51B5">Indigo</option>
                    </select>
                </div>

                <div class="flex space-x-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="for_student" id="editForStudent" value="1"
                               class="rounded border-gray-300 text-blue-500 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600">For Students</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="for_lecturer" id="editForLecturer" value="1"
                               class="rounded border-gray-300 text-blue-500 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600">For Lecturers</span>
                    </label>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="document.getElementById('editTaskModal').classList.add('hidden')"
                            class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600">
                        Update Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function editTask(id) {
    // Fetch task data
    fetch(`/timeframe/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('editName').value = data.name;
            document.getElementById('editDescription').value = data.description;
            document.getElementById('editStartDate').value = data.start_date;
            document.getElementById('editEndDate').value = data.end_date;
            document.getElementById('editColor').value = data.color;
            document.getElementById('editForStudent').checked = data.for_student;
            document.getElementById('editForLecturer').checked = data.for_lecturer;
            
            // Set form action
            document.getElementById('editTaskForm').action = `/timeframe/${id}/update`;
            
            // Show modal
            document.getElementById('editTaskModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load task data. Please try again.');
        });
}
</script>
@endsection

@section('styles')
<style>
.task-item {
    @apply bg-gray-50 rounded-lg p-4 mb-4 border border-gray-200;
}

.task-item:hover {
    @apply shadow-md transition-shadow duration-300;
}
</style>

@endsection 