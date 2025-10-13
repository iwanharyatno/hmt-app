<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My App')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-100 text-gray-900">

    @include('layouts.partials.navbar')

    <main class="container mx-auto ">
        @yield('content')
    </main>
    <x-feedback-fab />

    @stack('scripts')
</body>

</html>
