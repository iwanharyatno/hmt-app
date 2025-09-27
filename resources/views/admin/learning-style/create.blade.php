@extends('layouts.admin')

@section('title', 'Tambah Pertanyaan Learning Style')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md max-w-2xl mx-auto">
  <h1 class="text-2xl font-bold text-orange-600 mb-6">Tambah Pertanyaan</h1>

  <form action="" method="POST" class="space-y-6">
    @csrf

    <!-- Pertanyaan -->
    <div>
      <label class="block text-gray-700 mb-1">Pertanyaan</label>
      <textarea name="question" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-orange-400" rows="3"></textarea>
    </div>

    <!-- Jawaban -->
    <div>
      <label class="block text-gray-700 mb-2">Pilihan Jawaban</label>
      <div class="space-y-3">
        @for ($i = 1; $i <= 4; $i++)
        <div class="flex items-center gap-3">
          <input type="text" name="answers[]" placeholder="Jawaban {{ $i }}"
                 class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-orange-400">
          <input type="radio" name="correct_answer" value="{{ $i }}" class="text-orange-500">
        </div>
        @endfor
      </div>
      <p class="text-xs text-gray-500 mt-1">Pilih salah satu jawaban yang benar.</p>
    </div>

    <!-- Tombol -->
    <div class="flex justify-end space-x-3">
      <a href="{{ route('admin.learning-style.index') }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</a>
      <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600">Simpan</button>
    </div>
  </form>
</div>
@endsection
