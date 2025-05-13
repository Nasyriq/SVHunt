<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ config('app.name', 'Innovisory') }}</title>
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
        <!-- Main Content -->
        <main>
            @yield('content')
        </main>

        @yield('scripts')
    </body>
</html>
