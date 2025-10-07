@extends('layouts.app')

@section('title', 'Dashboard User')
<style>
    @keyframes moveBg {
        from {
            background-position: 0 0;
        }

        to {
            background-position: 1000px 1000px;
        }
    }
</style>
@section('content')
    <div class="bg-white pt-24 ">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 items-center gap-10 px-12 py-10">
            <!-- Left Text -->
            <div>
                <h1 class="text-4xl md:text-5xl font-bold text-gray-800 leading-tight mb-6">
                    Tes gratis yang membawa Anda lebih jauh
                </h1>
                <p class="text-gray-700 mb-6">
                    Tes psikologi untuk setiap pertanyaan karier dan pengembangan diri.
                    Mulai dari pemilihan karier hingga tes IQ, untuk penilaian kepribadian dan latihan tes kerja.
                </p>
                <a href="{{ route('user.quiz.learning-style') }}"
                    class="inline-flex items-center gap-2 bg-orange-600 text-white font-medium px-6 py-3 rounded-lg shadow hover:bg-orange-700 transition">
                    <i class="fas fa-rocket"></i>
                    Temukan tes Anda
                </a>
            </div>
            <!-- Right Illustration -->
            <div class="flex justify-center md:justify-end items-end">
                <img src="{{ asset('asset/iq ilustrasi.png') }}" alt="Ilustrasi Tes" class="w-full max-w-md md:max-w-lg">
            </div>
        </div>
    </div>

    <!-- Section Kuiz -->
    <div class="relative bg-white py-16 overflow-hidden">
        <!-- Background Animasi Puzzle -->
        <div class="absolute inset-0 opacity-20">
            <div
                class="w-full h-full bg-[url('https://t3.ftcdn.net/jpg/06/33/05/36/360_F_633053680_ykV9DREuXPgJLUj9hbtVdOZQzigWtKZZ.jpg')] opacity-5 animate-[moveBg_30s_linear_infinite]">
            </div>
        </div>

        @auth
            <div class="relative max-w-7xl mx-auto px-6 lg:px-12">
                <!-- Heading -->
                <div class="text-center mb-14" data-aos="fade-up">
                    <h1 class="text-4xl font-bold text-gray-800 mb-4">Selamat Pagi, User !</h1>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        Selamat datang di aplikasi <span class="font-semibold text-orange-600">Tes IQ</span>.
                        Silakan pilih kuis yang tersedia untuk mulai perjalananmu 🚀
                    </p>
                </div>
                <!-- Card Container -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <!-- Learning Style -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-md hover:shadow-2xl p-6 
              transition transform hover:-translate-y-1 duration-300 
              flex flex-col justify-between"
                        data-aos="fade-right">
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <div class="bg-gray-100 p-3 rounded-xl">
                                    <i class="fas fa-brain text-orange-600 text-2xl"></i>
                                </div>
                                <h2 class="text-xl font-semibold text-gray-800">Kuis Learning Style</h2>
                            </div>
                            <p class="text-gray-600 mb-6">
                                Kamu sudah pernah mengikuti kuis ini. Berikut hasil sebelumnya:
                                <span class="font-medium text-orange-600">Visual Learner</span>
                            </p>
                        </div>
                        <div class="flex gap-3 mt-auto">
                            <a href="{{ route('user.quiz.learning-style') }}"
                                class="px-5 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 text-gray-700 text-sm transition flex items-center gap-2">
                                <i class="fas fa-eye text-gray-500"></i> Lihat Hasil
                            </a>
                            <a href="{{ route('user.quiz.hmt') }}"
                                class="px-5 py-2 bg-orange-600 text-white rounded-lg hover:bg-indigo-700 text-sm transition flex items-center gap-2">
                                <i class="fas fa-arrow-right"></i> Lanjut ke HMT
                            </a>
                        </div>
                    </div>

                    <!-- HMT -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-md hover:shadow-2xl p-6 
              transition transform hover:-translate-y-1 duration-300 
              flex flex-col justify-between"
                        data-aos="fade-left">
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <div class="bg-gray-100 p-3 rounded-xl">
                                    <i class="fas fa-puzzle-piece text-orange-600 text-2xl"></i>
                                </div>
                                <h2 class="text-xl font-semibold text-gray-800">Kuis HMT (Hagen Matrices Test)</h2>
                            </div>
                            <p class="text-gray-600 mb-6">
                                Tes logika visual dengan batas waktu per soal <span class="font-medium">30 detik</span>.
                            </p>
                        </div>
                        <div class="mt-auto">
                            <a href="{{ route('user.quiz.hmt') }}"
                                class="px-5 py-2 bg-orange-600 text-white rounded-lg hover:bg-green-500 text-sm transition flex items-center gap-2">
                                <i class="fas fa-play-circle"></i> Mulai Tes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endauth
    </div>
    </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-50 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-6 md:px-12 py-10 grid grid-cols-1 md:grid-cols-3 gap-8">

            <!-- Logo & Description -->
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <img src="{{ asset('asset/fun run.png') }}" alt="Logo" class="w-10 h-10">
                    <span class="font-bold text-xl text-gray-800">IQ Test App</span>
                </div>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Platform tes psikologi & IQ modern untuk membantu kamu
                    menemukan potensi diri dan mengembangkan karir.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="font-semibold text-gray-700 mb-3">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="text-gray-600 hover:text-indigo-600 transition">Home</a></li>
                    <li><a href="{{ route('user.quiz.learning-style') }}"
                            class="text-gray-600 hover:text-indigo-600 transition">Learning Style</a></li>
                    <li><a href="{{ route('user.quiz.hmt') }}" class="text-gray-600 hover:text-indigo-600 transition">HMT
                            Test</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-indigo-600 transition">Contact</a></li>
                </ul>
            </div>

            <!-- Contact & Social -->
            <div>
                <h4 class="font-semibold text-gray-700 mb-3">Contact Us</h4>
                <p class="text-gray-600 text-sm mb-4">
                    Email: support@iqtestapp.com <br>
                    Phone: +62 812 3456 7890
                </p>
                <div class="flex gap-4">
                    <a href="#" class="text-gray-500 hover:text-indigo-600 transition"><i
                            class="fab fa-facebook fa-lg"></i></a>
                    <a href="#" class="text-gray-500 hover:text-indigo-600 transition"><i
                            class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" class="text-gray-500 hover:text-indigo-600 transition"><i
                            class="fab fa-instagram fa-lg"></i></a>
                </div>
            </div>
        </div>

        <!-- Bottom -->
        <div class="border-t border-gray-200 mt-6 py-4 text-center text-sm text-gray-500">
            © 2025 IQ Test App. All rights reserved.
        </div>
    </footer>

@endsection
