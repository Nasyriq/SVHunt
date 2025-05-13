<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Student Dashboard') - Innovisory</title>
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
                    <span class="ml-2 text-xl font-semibold">Student Dashboard</span>
                </div>
                <div class="flex items-center space-x-8">
                    <a href="{{ route('student.dashboard') }}" 
                       class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('student.topic.index') }}" 
                       class="nav-link {{ request()->routeIs('student.topic.*') ? 'active' : '' }}">
                        Topic
                    </a>
                    <a href="{{ route('student.appointment.index') }}" 
                       class="nav-link {{ request()->routeIs('student.appointment.*') ? 'active' : '' }}">
                        Appointment
                    </a>
                    <a href="{{ route('student.profile.index') }}" 
                       class="nav-link {{ request()->routeIs('student.profile.*') ? 'active' : '' }}">
                        Profile
                    </a>
                    <form method="POST" action="{{ route('student.logout') }}" class="ml-4">
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