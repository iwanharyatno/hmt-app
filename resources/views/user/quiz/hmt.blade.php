@extends('layouts.app')

@section('title', 'Dashboard User')

@section('content')
<!-- HMT Quiz -->
<div class="bg-white py-14 pt-32">
  <div class="max-w-4xl mx-auto px-6 lg:px-12">

    <!-- Heading -->
    <div class="text-center mb-10" data-aos="fade-up">
      <h1 class="text-3xl font-bold text-gray-800 mb-3">Kuis HMT</h1>
      <p class="text-gray-600">
        Pilih jawaban yang tepat dalam waktu <span class="font-semibold text-pink-600">30 detik</span> per soal.
      </p>
    </div>

    <!-- Pertanyaan -->
    <div class="bg-white rounded-2xl shadow-md p-6 mb-8" data-aos="fade-up">
      <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
        <i class="fas fa-puzzle-piece text-pink-600"></i>
        Pertanyaan 1 dari 20
      </h2>
      
      <!-- Gambar Soal -->
      <div class="flex justify-center mb-6">
        <img src="https://sp-ao.shortpixel.ai/client/to_webp,q_glossy,ret_img,w_600,h_324/https://glints.com/id/lowongan/wp-content/uploads/2020/09/wartegg-2-aspghandwriting-org.png" alt="Soal HMT" class="rounded-lg shadow">
      </div>

      <!-- Pilihan Jawaban -->
      <div class="grid grid-cols-3 gap-4">
        <button class="p-3 bg-gray-100 rounded-lg hover:bg-pink-100 transition">
          <img src="https://awsimages.detik.net.id/community/media/visual/2022/08/04/tes-wartegg.jpeg?w=600&q=90" class="mx-auto">
        </button>
        <button class="p-3 bg-gray-100 rounded-lg hover:bg-pink-100 transition">
          <img src="https://awsimages.detik.net.id/community/media/visual/2022/08/04/tes-wartegg.jpeg?w=600&q=90" class="mx-auto">
        </button>
        <button class="p-3 bg-gray-100 rounded-lg hover:bg-pink-100 transition">
          <img src="https://awsimages.detik.net.id/community/media/visual/2022/08/04/tes-wartegg.jpeg?w=600&q=90" class="mx-auto">
        </button>
        <button class="p-3 bg-gray-100 rounded-lg hover:bg-pink-100 transition">
          <img src="https://awsimages.detik.net.id/community/media/visual/2022/08/04/tes-wartegg.jpeg?w=600&q=90" class="mx-auto">
        </button>
        <button class="p-3 bg-gray-100 rounded-lg hover:bg-pink-100 transition">
          <img src="https://awsimages.detik.net.id/community/media/visual/2022/08/04/tes-wartegg.jpeg?w=600&q=90" class="mx-auto">
        </button>
        <button class="p-3 bg-gray-100 rounded-lg hover:bg-pink-100 transition">
          <img src="https://awsimages.detik.net.id/community/media/visual/2022/08/04/tes-wartegg.jpeg?w=600&q=90" class="mx-auto">
        </button>
      </div>
    </div>

    <!-- Timer & Tombol Navigasi -->
    <div class="flex justify-between items-center">
      <div class="text-sm text-gray-600 flex items-center gap-2">
        <i class="fas fa-clock text-pink-500"></i>
        Sisa waktu: <span class="font-semibold">29 detik</span>
      </div>
      <button class="px-5 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition">
        Selanjutnya <i class="fas fa-arrow-right"></i>
      </button>
    </div>

  </div>
</div>
@endsection