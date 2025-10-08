<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title') | Admin Panel</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
a    body {
      font-family: 'Poppins', sans-serif;
    }
  </style>
  @stack('styles')
</head>
<body class="bg-gray-100">

  <div x-data="{ open: false }" class="flex h-screen" x-cloak>
    <!-- Sidebar -->
    <aside :class="open ? 'translate-x-0' : '-translate-x-full'"
           class="fixed z-40 inset-y-0 left-0 w-64 bg-orange-800 text-white transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
      <div class="p-5 flex items-center justify-between lg:justify-center">
        <h1 class="text-2xl font-bold">Admin Panel</h1>
        <!-- Tombol Close di Mobile -->
        <button @click="open = false" class="lg:hidden text-white">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <nav class="space-y-2 p-4">
        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded hover:bg-orange-500">
          <i class="fas fa-home mr-2"></i> Dashboard
        </a>
        <a href="{{ route('admin.hmt.index') }}" class="block px-3 py-2 rounded hover:bg-orange-500">
          <i class="fas fa-puzzle-piece mr-2"></i> HMT Quiz
        </a>
        <a href="{{ route('admin.hmt.history') }}" class="block px-3 py-2 rounded hover:bg-orange-500">
          <i class="fas fa-puzzle-piece mr-2"></i> Jawaban HMT
        </a>
        <a href="{{ route('admin.learning-style.index') }}" class="block px-3 py-2 rounded hover:bg-orange-500">
          <i class="fas fa-brain mr-2"></i> Learning Style Quiz
        </a>
        <a href="{{ route('admin.learning-style.history') }}" class="block px-3 py-2 rounded hover:bg-orange-500">
          <i class="fas fa-brain mr-2"></i> Jawaban Learning Style
        </a>
        <a href="{{ route('admin.settings.index') }}" class="block px-3 py-2 rounded hover:bg-orange-500">
          <i class="fas fa-gear mr-2"></i> Pengaturan
        </a>
      </nav>
    </aside>

    <!-- Konten Utama -->
    <div class="flex-1 flex flex-col">
      <!-- Navbar -->
      <header class="bg-white shadow px-4 py-3 flex items-center justify-between lg:hidden">
        <button @click="open = true" class="text-gray-700">
          <i class="fas fa-bars text-2xl"></i>
        </button>
        <h2 class="text-lg font-semibold">Admin Panel</h2>
      </header>

      <!-- Main Content -->
      <main class="flex-1 p-6 overflow-y-auto">
        @yield('content')
      </main>
    </div>
  </div>

  @stack('scripts')
</body>
</html>
