@extends('layouts.coordinator')

@section('title', 'Quota Management')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Quota Management</h2>
        <button onclick="document.getElementById('addQuotaModal').classList.remove('hidden')" 
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Assign New Quota
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

    <!-- Add Quota Modal -->
    <div id="addQuotaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-6 border w-[500px] shadow-lg rounded-md bg-white">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Assign New Quota</h3>
            
            <form action="{{ route('coordinator.quotas.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lecturer</label>
                    <select name="lecturer_id" required
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Lecturer</option>
                        @foreach($lecturers as $lecturer)
                            <option value="{{ $lecturer->id }}">
                                {{ $lecturer->name }} ({{ $lecturer->staff_id }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Maximum Supervisees</label>
                    <input type="number" name="max_supervisees" required min="0" max="20"
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="document.getElementById('addQuotaModal').classList.add('hidden')"
                            class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600">
                        Assign Quota
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Quotas Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lecturer Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Research Group</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current/Max</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($quotas as $quota)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $quota->lecturer->staff_id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $quota->lecturer->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">
                            {{ $quota->lecturer->research_group }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="font-semibold {{ $quota->current_supervisees >= $quota->max_supervisees ? 'text-red-600' : 'text-green-600' }}">
                            {{ $quota->current_supervisees }}/{{ $quota->max_supervisees }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex space-x-2">
                            <button onclick="editQuota({{ $quota->id }}, {{ $quota->max_supervisees }})" 
                                    class="text-blue-500 hover:text-blue-700 transition-colors">
                                <i class="fas fa-edit"></i>
                            </button>
                            @if($quota->current_supervisees == 0)
                            <form action="{{ route('coordinator.quotas.destroy', $quota) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this quota?');"
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4 border-t">
            {{ $quotas->links() }}
        </div>
    </div>
</div>
@endsection 

@section('scripts')
<script>
function editQuota(id, currentMax) {
    const newMax = prompt('Enter new maximum supervisees (0-20):', currentMax);
    if (newMax === null) return;
    
    if (newMax < 0 || newMax > 20) {
        alert('Maximum supervisees must be between 0 and 20');
        return;
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/quotas/${id}/update`;
    
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'PUT';
    
    const maxInput = document.createElement('input');
    maxInput.type = 'hidden';
    maxInput.name = 'max_supervisees';
    maxInput.value = newMax;
    
    form.appendChild(csrfInput);
    form.appendChild(methodInput);
    form.appendChild(maxInput);
    
    document.body.appendChild(form);
    form.submit();
}
</script>

@endsection 