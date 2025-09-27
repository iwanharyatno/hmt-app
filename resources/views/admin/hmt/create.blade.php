@extends('layouts.admin')

@section('title', 'Tambah Soal HMT')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md max-w-3xl mx-auto">
  <h1 class="text-2xl font-bold text-orange-600 mb-6">Tambah Soal HMT</h1>

  <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <!-- Upload Gambar Soal -->
    <div>
      <label class="block text-gray-700 mb-1">Gambar Soal</label>
      <input type="file" name="question_image" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-orange-400">
    </div>

    <!-- Upload Gambar Jawaban -->
    <div>
      <label class="block text-gray-700 mb-2">Pilihan Jawaban</label>
      <div class="grid grid-cols-2 gap-4">
        @for ($i = 1; $i <= 4; $i++)
          <div class="border p-3 rounded-lg">
            <label class="block text-gray-600 mb-1">Jawaban {{ $i }}</label>
            <input type="file" name="answers[]" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-orange-400">
            <div class="mt-2 flex items-center">
              <input type="radio" name="correct_answer" value="{{ $i }}" class="mr-2">
              <span class="text-sm text-gray-600">Tandai sebagai benar</span>
            </div>
          </div>
        @endfor
      </div>
    </div>

    <!-- Tombol -->
    <div class="flex justify-end space-x-3">
      <a href="{{ route('admin.hmt.index') }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</a>
      <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600">Simpan</button>
    </div>
  </form>
</div>
@endsection
