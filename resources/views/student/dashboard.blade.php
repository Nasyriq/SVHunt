@extends('layouts.student')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto px-4">
    <!-- Student Info -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Welcome, {{ Auth::guard('student')->user()->name }}</h2>
                <p class="text-gray-600">Research Group: {{ Auth::guard('student')->user()->research_group ?? 'Not Assigned' }}</p>
            </div>
            <div class="text-right">
                <p class="text-gray-600">Matric ID: {{ Auth::guard('student')->user()->matric_id }}</p>
                <p class="text-gray-600">Program: {{ Auth::guard('student')->user()->program }}</p>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Pending Topic Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                    <i class="fas fa-file-alt text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Pending Topic</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ $pendingTopicCount ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Appointment Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                    <i class="fas fa-calendar-check text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Pending Appointments</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ $pendingAppointmentCount ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeframe Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Current Timeline</h3>
        <div class="space-y-4">
            @forelse($tasks ?? [] as $task)
            <div class="border-l-4 pl-4" style="border-color: {{ $task->color }}">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-semibold text-gray-700">{{ $task->name }}</h4>
                        <p class="text-sm text-gray-600">
                            {{ $task->start_date->format('d M Y, H:i') }} - 
                            {{ $task->end_date->format('d M Y, H:i') }}
                        </p>
                        @if($task->description)
                        <p class="text-gray-600 mt-1">{{ $task->description }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <span class="inline-block px-2 py-1 text-sm rounded-full"
                              style="background-color: {{ $task->color }}20; color: {{ $task->color }}">
                            {{ $task->status }}
                        </span>
                        <p class="text-sm font-medium mt-1">{{ $task->progress_percentage }}% Complete</p>
                    </div>
                </div>
                <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                    <div class="h-2 rounded-full" 
                         style="width: {{ $task->progress_percentage }}%; background-color: {{ $task->color }}"></div>
                </div>
            </div>
            @empty
            <p class="text-gray-600 text-center py-4">No active tasks at the moment.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection 