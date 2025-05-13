<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Lecturer Dashboard') - Innovisory</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background-color: #C8D9E6;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <img src="{{ asset('images/innovisory.png') }}" alt="Logo" class="h-8 w-auto">
                    <span class="ml-2 text-xl font-semibold">Lecturer Dashboard</span>
                </div>
                <div class="flex items-center space-x-8">
                    <a href="{{ route('lecturer.dashboard') }}" 
                       class="nav-link {{ request()->routeIs('lecturer.dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('lecturer.topic.index') }}" 
                       class="nav-link {{ request()->routeIs('lecturer.topic.*') ? 'active' : '' }}">
                        Topic
                    </a>
                    <a href="{{ route('lecturer.appointment.index') }}" 
                       class="nav-link {{ request()->routeIs('lecturer.appointment.*') ? 'active' : '' }}">
                        Appointment
                    </a>
                    <a href="{{ route('lecturer.profile.index') }}" 
                       class="nav-link {{ request()->routeIs('lecturer.profile.*') ? 'active' : '' }}">
                        Profile
                    </a>
                    <form method="POST" action="{{ route('lecturer.logout') }}" class="ml-4">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-gray-900">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-6">
        @yield('content')
    </main>

    @yield('scripts')
</body>
</html> 