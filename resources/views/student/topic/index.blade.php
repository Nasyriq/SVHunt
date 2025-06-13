@extends('layouts.student')

@section('title', 'Topic Management')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Topic Management</h2>
        <button onclick="document.getElementById('addTopicModal').classList.remove('hidden')" 
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Submit New Topic
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

    <!-- Topics List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Research Area</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supervisor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($topics as $topic)
                <tr>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $topic->title }}</div>
                        <div class="text-sm text-gray-500">{{ Str::limit($topic->description, 50) }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $topic->research_area }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $topic->lecturer->name }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($topic->status === 'approved') bg-green-100 text-green-800
                            @elseif($topic->status === 'rejected') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($topic->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        @if($topic->status === 'pending')
                        <button onclick="editTopic({{ $topic->id }})" class="text-blue-600 hover:text-blue-900 mr-3">
                            Edit
                        </button>
                        <form action="{{ route('student.topic.destroy', $topic) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900"
                                    onclick="return confirm('Are you sure you want to delete this topic?')">
                                Delete
                            </button>
                        </form>
                        @endif
                        @if($topic->feedback)
                        <button onclick="viewFeedback('{{ $topic->feedback }}')" class="text-gray-600 hover:text-gray-900 ml-3">
                            View Feedback
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        No topics submitted yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Add Topic Modal -->
    <div id="addTopicModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium">Submit New Topic</h3>
                <button onclick="document.getElementById('addTopicModal').classList.add('hidden')"
                        class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('student.topic.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" rows="4" required
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Research Group</label>
                        <select name="research_group" id="researchGroup" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            <option value="">Select a research group</option>
                            @foreach($researchGroups as $code => $name)
                            <option value="{{ $code }}">{{ $code }} - {{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Supervisor</label>
                        <select name="lecturer_id" id="lecturerSelect" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            <option value="">Select a supervisor</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button"
                            onclick="document.getElementById('addTopicModal').classList.add('hidden')"
                            class="px-4 py-2 border rounded-md text-gray-600 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Feedback Modal -->
    <div id="feedbackModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium">Supervisor Feedback</h3>
                <button onclick="document.getElementById('feedbackModal').classList.add('hidden')"
                        class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="feedbackContent" class="text-gray-600"></div>
            <div class="mt-6 flex justify-end">
                <button onclick="document.getElementById('feedbackModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Topic Modal -->
    <div id="editTopicModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium">Edit Topic</h3>
                <button onclick="document.getElementById('editTopicModal').classList.add('hidden')"
                        class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editTopicForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" id="editTitle" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="editDescription" rows="4" required
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Research Area</label>
                    <input type="text" name="research_area" id="editResearchArea" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Supervisor</label>
                    <select name="lecturer_id" id="editLecturerId" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        <option value="">Select a supervisor</option>
                        @foreach($lecturers as $lecturer)
                        <option value="{{ $lecturer->id }}">{{ $lecturer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="document.getElementById('editTopicModal').classList.add('hidden')"
                            class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600">
                        Update Topic
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function viewFeedback(feedback) {
    document.getElementById('feedbackContent').textContent = feedback;
    document.getElementById('feedbackModal').classList.remove('hidden');
}

// Pass all lecturers to JS
const lecturers = @json($lecturers);

document.addEventListener('DOMContentLoaded', function() {
    const researchGroupSelect = document.getElementById('researchGroup');
    const lecturerSelect = document.getElementById('lecturerSelect');

    function populateLecturers(group) {
        lecturerSelect.innerHTML = '<option value="">Select a supervisor</option>';
        if (!group) return;
        lecturers.forEach(lecturer => {
            if (lecturer.research_group === group) {
                const option = document.createElement('option');
                option.value = lecturer.id;
                option.textContent = lecturer.name;
                lecturerSelect.appendChild(option);
            }
        });
    }

    researchGroupSelect.addEventListener('change', function() {
        populateLecturers(this.value);
    });

    // Optionally, populate on page load if old value exists
    @if(old('research_group'))
        populateLecturers(@json(old('research_group')));
        @if(old('lecturer_id'))
            lecturerSelect.value = @json(old('lecturer_id'));
        @endif
    @endif
});

function editTopic(id) {
    // Fetch topic data
    fetch(`/student/topics/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('editTitle').value = data.title;
            document.getElementById('editDescription').value = data.description;
            document.getElementById('editResearchArea').value = data.research_area;
            document.getElementById('editLecturerId').value = data.lecturer_id;
            
            // Set form action
            document.getElementById('editTopicForm').action = `/student/topics/${id}/update`;
            
            // Show modal
            document.getElementById('editTopicModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load topic data. Please try again.');
        });
}
</script>
@endsection 