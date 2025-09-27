<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My App')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
<!-- Tambahkan di dalam <head> -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    @stack('styles')
</head>

<body class="bg-gray-100 text-gray-900">

    @include('layouts.partials.navbar')

    <main class="container mx-auto ">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
