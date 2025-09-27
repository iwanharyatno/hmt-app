@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<div class="bg-white pt-24">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-10">
        <!-- Heading -->
        <div class="text-center mb-14" data-aos="fade-up">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Hubungi Kami</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Punya pertanyaan, kritik, atau saran?  
                Silakan kirimkan pesan melalui form berikut atau hubungi kontak resmi kami 📩
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            
            <!-- Contact Form -->
            <div class="bg-gray-50 p-8 rounded-2xl shadow" data-aos="fade-right">
                <form action="" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pesan</label>
                        <textarea name="message" rows="5" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                    </div>
                    
                    <button type="submit"
                        class="w-full bg-indigo-600 text-white font-medium px-6 py-3 rounded-lg shadow hover:bg-indigo-700 transition">
                        <i class="fas fa-paper-plane"></i> Kirim Pesan
                    </button>
                </form>
            </div>

            <!-- Contact Info -->
            <div class="space-y-8" data-aos="fade-left">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Informasi Kontak</h2>
                    <p class="text-gray-600 mb-3">
                        Kami senang mendengar dari Anda! Hubungi kami melalui:
                    </p>
                    <ul class="space-y-3 text-gray-700">
                        <li class="flex items-center gap-3">
                            <i class="fas fa-envelope text-indigo-600"></i>
                            support@iqtestapp.com
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fas fa-phone-alt text-indigo-600"></i>
                            +62 812 3456 7890
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fas fa-map-marker-alt text-indigo-600"></i>
                            Jl. Mawar No. 123, Jakarta, Indonesia
                        </li>
                    </ul>
                </div>

                <!-- Map -->
                <div class="rounded-xl overflow-hidden shadow-lg">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.7322249362183!2d106.82715367475418!3d-6.167416960437717!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f46c1f5dd47f%3A0x8fdbf6e9ef4fb3d9!2sMonas!5e0!3m2!1sen!2sid!4v1678627111111"
                        width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy">
                    </iframe>
                </div>
            </div>

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
                <li><a href="{{ route('user.dashboard') }}" class="text-gray-600 hover:text-indigo-600 transition">Home</a></li>
                <li><a href="{{ route('user.quiz.learning-style') }}" class="text-gray-600 hover:text-indigo-600 transition">Learning Style</a></li>
                <li><a href="{{ route('user.quiz.hmt') }}" class="text-gray-600 hover:text-indigo-600 transition">HMT Test</a></li>
                <li><a href="{{ route('user.contact') }}" class="text-gray-600 hover:text-indigo-600 transition">Contact</a></li>
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
                <a href="#" class="text-gray-500 hover:text-indigo-600 transition"><i class="fab fa-facebook fa-lg"></i></a>
                <a href="#" class="text-gray-500 hover:text-indigo-600 transition"><i class="fab fa-twitter fa-lg"></i></a>
                <a href="#" class="text-gray-500 hover:text-indigo-600 transition"><i class="fab fa-instagram fa-lg"></i></a>
            </div>
        </div>
    </div>

    <!-- Bottom -->
    <div class="border-t border-gray-200 mt-6 py-4 text-center text-sm text-gray-500">
        © 2025 IQ Test App. All rights reserved.
    </div>
</footer>
@endsection
