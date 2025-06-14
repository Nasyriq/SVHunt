<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Innovisory System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <!-- Add FontAwesome for modern icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary-bg: #C8D9E6;
            --primary-dark: #2C3E50;
            --accent-color: #3498DB;
            --hover-color: #2980B9;
            --gradient-start: #2193b0;
            --gradient-end: #6dd5ed;
        }

        body {
            background-color: var(--primary-bg);
            font-family: 'Arial', sans-serif;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .icon-wrapper {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            transition: transform 0.3s ease;
        }

        .icon-wrapper i {
            font-size: 2.5rem;
            color: white;
        }

        .login-card:hover .icon-wrapper {
            transform: scale(1.1) rotate(5deg);
        }

        .login-btn {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            transition: all 0.3s ease;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(33, 147, 176, 0.3);
        }

        .logo-container {
            position: absolute;
            top: 2rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 20;
            text-align: center;
            margin-bottom: 3rem;
        }

        .logo-container img {
            height: 150px;
            width: auto;
            margin: 0 auto;
        }

        .welcome-text {
            margin-top: 4rem;
        }

        @media (max-width: 640px) {
            .logo-container {
                position: relative;
                top: 1rem;
                transform: translateX(-50%);
                margin-bottom: 2rem;
            }
            .welcome-text {
                margin-top: 2rem;
            }
        }
    </style>
</head>
<body class="bg-blue-200 min-h-screen flex flex-col justify-between">
    <div class="flex flex-col items-center justify-center flex-grow w-full pt-12">
        <img src="{{ asset('images/innovisory.png') }}" alt="Innovisory Logo" class="mx-auto mb-8" style="height: 150px; width: auto;">
        <h1 class="text-5xl font-bold text-center mb-2">Welcome to Innovisory!</h1>
        <p class="text-xl text-center mb-8">Your Academic Advisory Management Solution</p>
        <!-- Menu Cards Section -->
        <div class="flex flex-col md:flex-row items-center justify-center gap-8 mb-16 w-full max-w-5xl">
            <!-- Student Portal Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8 flex flex-col items-center w-80 mb-8 md:mb-0">
                        <div class="icon-wrapper">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-gray-800">Student Portal</h3>
                        <p class="text-gray-600 mb-8">Access your academic advisory services and schedule meetings with your advisor.</p>
                        <a href="/student/login" class="login-btn text-white inline-block hover:shadow-lg">
                            Student Login
                        </a>
                    </div>
            <!-- Lecturer Portal Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8 flex flex-col items-center w-80 mb-8 md:mb-0">
                        <div class="icon-wrapper">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-gray-800">Lecturer Portal</h3>
                        <p class="text-gray-600 mb-8">Manage your advisees and maintain records of advisory sessions.</p>
                        <a href="/lecturer/login" class="login-btn text-white inline-block hover:shadow-lg">
                            Lecturer Login
                        </a>
                    </div>
            <!-- Coordinator Portal Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8 flex flex-col items-center w-80">
                        <div class="icon-wrapper">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-gray-800">Coordinator Portal</h3>
                        <p class="text-gray-600 mb-8">Oversee the advisory system and manage lecturer-student assignments.</p>
                        <a href="/coordinator/login" class="login-btn text-white inline-block hover:shadow-lg">
                            Coordinator Login
                        </a>
                    </div>
                </div>
            </div>
    <footer class="w-full bg-white text-center py-4 shadow-inner mt-8">
        <span class="text-gray-600">&copy; {{ date('Y') }} Innovisory. All rights reserved.</span>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>
</body>
</html>
