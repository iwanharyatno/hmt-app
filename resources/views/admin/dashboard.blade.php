@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Admin</h1>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

  <!-- Card Total Pertanyaan HMT -->
  <div class="bg-white p-6 rounded-lg shadow flex items-center justify-between">
    <div>
      <h2 class="text-gray-600 text-sm font-medium">Total Pertanyaan HMT</h2>
      <p class="text-3xl font-bold text-orange-500 mt-2">120</p>
      <span class="text-xs text-gray-400">+5 baru minggu ini</span>
    </div>
    <div class="bg-orange-100 p-3 rounded-full">
      <svg xmlns="http://www.w3.org/2000/svg" 
           class="h-8 w-8 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2v-7H3v7a2 2 0 002 2z"/>
      </svg>
    </div>
  </div>

  <!-- Card Total Pertanyaan Learning Style -->
  <div class="bg-white p-6 rounded-lg shadow flex items-center justify-between">
    <div>
      <h2 class="text-gray-600 text-sm font-medium">Total Pertanyaan Learning Style</h2>
      <p class="text-3xl font-bold text-pink-500 mt-2">85</p>
      <span class="text-xs text-gray-400">+2 update terbaru</span>
    </div>
    <div class="bg-pink-100 p-3 rounded-full">
      <svg xmlns="http://www.w3.org/2000/svg" 
           class="h-8 w-8 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M12 14l9-5-9-5-9 5 9 5zm0 0v7"/>
      </svg>
    </div>
  </div>

  <!-- Card Total User -->
  <div class="bg-white p-6 rounded-lg shadow flex items-center justify-between">
    <div>
      <h2 class="text-gray-600 text-sm font-medium">Total User</h2>
      <p class="text-3xl font-bold text-green-500 mt-2">450</p>
      <span class="text-xs text-gray-400">+20 user baru bulan ini</span>
    </div>
    <div class="bg-green-100 p-3 rounded-full">
      <svg xmlns="http://www.w3.org/2000/svg" 
           class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6v-2a4 4 0 014-4h1m-6 6h-5a4 4 0 01-4-4v-1m9-5a4 4 0 11-8 0 4 4 0 018 0z"/>
      </svg>
    </div>
  </div>

</div>
@endsection
