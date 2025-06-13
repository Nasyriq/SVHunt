<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturer Login - Innovisory</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-bg: #C8D9E6;
            --gradient-start: #2193b0;
            --gradient-end: #6dd5ed;
        }

        body {
            background-color: var(--primary-bg);
            font-family: 'Arial', sans-serif;
        }

        .login-container {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            min-height: 100vh;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .login-btn {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(33, 147, 176, 0.3);
        }
    </style>
</head>
<body>
    <div class="login-container flex items-center justify-center p-6">
        <div class="login-card w-full max-w-md p-8">
            <div class="text-center mb-8">
                <img src="{{ asset('images/innovisory.png') }}" alt="Innovisory Logo" class="h-20 mx-auto mb-4">
                <h2 class="text-3xl font-bold text-gray-800">Lecturer Login</h2>
            </div>

            @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form class="mt-8 space-y-6" action="{{ route('lecturer.login') }}" method="POST">
            @csrf
                <div class="rounded-md shadow-sm -space-y-px">
                    <label for="staff_id" class="block text-gray-700 font-medium mb-2">Staff ID</label>
                    <input id="staff_id" name="staff_id" type="text" required 
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-gray-900 placeholder-gray-400"
                    placeholder="Staff ID">
                </div>
                <div class="rounded-md shadow-sm -space-y-px">
                    <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                    <input id="password" name="password" type="password" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-gray-900 placeholder-gray-400"
                    placeholder="Password">
                </div>
            @error('staff_id')
            <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror

            <button type="submit" class="login-btn w-full text-white py-3 px-6 rounded-lg font-medium">
                Login
            </button>
            <div class="mt-6 text-center">
                <a href="/" class="text-blue-600 hover:text-blue-800">Back to Home</a>
            </div>
        </form>
        </div>
    </div>
</body>
</html> 