@extends('layouts.app')

@section('title', 'Dashboard User')

@section('content')
<!-- Learning Style Quiz -->
<div class="bg-white py-14 pt-32">
  <div class="max-w-3xl mx-auto px-6 lg:px-12">

    <!-- Heading -->
    <div class="text-center mb-10" data-aos="fade-up">
      <h1 class="text-3xl font-bold text-gray-800 mb-3">Kuis Learning Style</h1>
      <p class="text-gray-600">
        Pilih jawaban yang paling menggambarkan dirimu.
      </p>
    </div>

    <!-- Pertanyaan -->
    <div class="bg-white rounded-2xl shadow-md p-6 mb-8" data-aos="fade-up">
      <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
        <i class="fas fa-question-circle text-indigo-600"></i>
        Pertanyaan 1 dari 10
      </h2>
      <p class="text-gray-700 mb-6">
        Saat belajar sesuatu yang baru, cara apa yang paling kamu sukai?
      </p>

      <!-- Opsi Jawaban -->
      <div class="space-y-3">
        <button class="w-full text-left px-4 py-3 bg-gray-100 rounded-lg hover:bg-indigo-100 flex items-center gap-3 transition">
          <i class="fas fa-eye text-indigo-500"></i> Melihat gambar atau diagram
        </button>
        <button class="w-full text-left px-4 py-3 bg-gray-100 rounded-lg hover:bg-indigo-100 flex items-center gap-3 transition">
          <i class="fas fa-headphones text-indigo-500"></i> Mendengarkan penjelasan
        </button>
        <button class="w-full text-left px-4 py-3 bg-gray-100 rounded-lg hover:bg-indigo-100 flex items-center gap-3 transition">
          <i class="fas fa-running text-indigo-500"></i> Mencoba langsung dengan praktik
        </button>
      </div>
    </div>

    <!-- Tombol Navigasi -->
    <div class="flex justify-between">
      <button class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
        <i class="fas fa-arrow-left"></i> Sebelumnya
      </button>
      <button class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
        Selanjutnya <i class="fas fa-arrow-right"></i>
      </button>
    </div>

  </div>
</div>
@endsection