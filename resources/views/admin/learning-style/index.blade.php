@extends('layouts.admin')

@section('title', 'Learning Style Quiz')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md">
  <!-- Header -->
  <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4">
    <h1 class="text-2xl font-bold text-orange-600">Daftar Pertanyaan Learning Style</h1>
    <a href="{{ route('admin.learning-style.create') }}" 
       class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition text-center">
      <i class="fas fa-plus"></i> Tambah Pertanyaan
    </a>
  </div>

  <!-- Table (desktop) -->
  <div class="hidden sm:block overflow-hidden rounded-lg border border-gray-200">
    <table class="w-full text-sm md:text-base">
      <thead class="bg-orange-100 text-orange-700">
        <tr>
          <th class="py-3 px-4 text-left">No</th>
          <th class="py-3 px-4 text-left">Pertanyaan</th>
          <th class="py-3 px-4 text-left">Aksi</th>
        </tr>
      </thead>
      <tbody class="text-gray-700">
        @foreach(range(1,5) as $i)
        <tr class="border-t hover:bg-orange-50">
          <td class="py-3 px-4">{{ $i }}</td>
          <td class="py-3 px-4">Contoh pertanyaan learning style ke-{{ $i }}</td>
          <td class="py-3 px-4 space-x-2">
            <a href="" 
               class="inline-block px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition">
              <i class="fas fa-edit"></i>
            </a>
            <form action="" method="POST" class="inline">
              @csrf
              @method('DELETE')
              <button class="inline-block px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition"
                      onclick="return confirm('Yakin hapus pertanyaan ini?')">
                <i class="fas fa-trash"></i>
              </button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <!-- Card View (mobile) -->
  <div class="space-y-4 sm:hidden">
    @foreach(range(1,5) as $i)
    <div class="border rounded-lg p-4 shadow hover:bg-orange-50">
      <div class="flex justify-between items-center mb-2">
        <span class="font-semibold text-orange-600">Pertanyaan {{ $i }}</span>
        <div class="space-x-2">
          <a href="" class="inline-block px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition text-sm">
            <i class="fas fa-edit"></i>
          </a>
          <form action="" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button class="inline-block px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition text-sm"
                    onclick="return confirm('Yakin hapus pertanyaan ini?')">
              <i class="fas fa-trash"></i>
            </button>
          </form>
        </div>
      </div>
      <p class="text-gray-700">Contoh pertanyaan learning style ke-{{ $i }}</p>
    </div>
    @endforeach
  </div>
</div>
@endsection
