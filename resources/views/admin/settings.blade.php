@extends('layouts.admin')

@section('title', 'Pengaturan')

@section('content')
    <div class="bg-white p-6 rounded-xl shadow-md">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4">
            <h1 class="text-2xl font-bold text-orange-600">Pengaturan</h1>
            <div class="flex gap-2">
                <button form="settingsForm"
                    class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-center">
                    <i class="fas fa-save"></i> Simpan Pengaturan
                </button>
            </div>
        </div>

        @session('success')
            <div class="bg-green-200/50 border border-green-600 rounded-lg p-4 mb-4">
                {{ session('success') }}
            </div>
        @endsession

        <form id="settingsForm" action="{{ route('admin.settings.update') }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            {{-- =========================
                HMT SECTION
            ========================== --}}
            <div class="border border-orange-200 rounded-lg shadow-sm">
                <div class="bg-orange-100 px-4 py-3 rounded-t-lg flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-orange-700 flex items-center gap-2">
                        <i class="fas fa-puzzle-piece"></i> Kuis HMT
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Durasi Waktu per Soal -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Durasi Waktu per Soal (detik)
                        </label>
                        <input type="number" name="{{ \App\Models\Setting::HMT_DURATION }}"
                            value="{{ old(\App\Models\Setting::HMT_DURATION, $settings[\App\Models\Setting::HMT_DURATION] ?? 30) }}"
                            min="5" max="300" step="1"
                            class="border border-gray-300 rounded-lg px-3 py-2 w-40 focus:ring-2 focus:ring-orange-500 focus:outline-none"
                            placeholder="Contoh: 30">
                        <p class="text-xs text-gray-500 mt-1">Menentukan batas waktu pengerjaan setiap soal HMT (default 30
                            detik).</p>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Deskripsi / Petunjuk Kuis HMT
                        </label>
                        <input id="hmt_description" type="hidden" name="{{ \App\Models\Setting::HMT_DESCRIPTION }}"
                            value="{{ old(\App\Models\Setting::HMT_DESCRIPTION, $settings[\App\Models\Setting::HMT_DESCRIPTION]) }}">
                        <trix-editor input="hmt_description"
                            class="trix-content custom-trix-content border rounded-lg p-2 bg-white min-h-64"></trix-editor>
                    </div>

                    <!-- Privacy Policy -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Privacy Policy Kuis HMT
                        </label>
                        <input id="hmt_privacy" type="hidden" name="{{ \App\Models\Setting::HMT_PRIVACY }}"
                            value="{{ old(\App\Models\Setting::HMT_PRIVACY, $settings[\App\Models\Setting::HMT_PRIVACY]) }}">
                        <trix-editor input="hmt_privacy"
                            class="trix-content custom-trix-content border rounded-lg p-2 bg-white min-h-64"></trix-editor>
                    </div>
                </div>
            </div>

            {{-- =========================
                LEARNING STYLE SECTION
            ========================== --}}
            <div class="border border-pink-200 rounded-lg shadow-sm">
                <div class="bg-pink-100 px-4 py-3 rounded-t-lg flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-pink-700 flex items-center gap-2">
                        <i class="fas fa-brain"></i> Kuisioner Learning Style
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Deskripsi / Petunjuk Kuisioner Learning Style
                        </label>
                        <input id="ls_description" type="hidden" name="{{ \App\Models\Setting::LS_DESCRIPTION }}"
                            value="{{ old(\App\Models\Setting::LS_DESCRIPTION, $settings[\App\Models\Setting::LS_DESCRIPTION]) }}">
                        <trix-editor input="ls_description"
                            class="trix-content custom-trix-content border rounded-lg p-2 bg-white min-h-64"></trix-editor>
                    </div>

                    <!-- Privacy Policy -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Privacy Policy Kuisioner Learning Style
                        </label>
                        <input id="ls_privacy" type="hidden" name="{{ \App\Models\Setting::LS_PRIVACY }}"
                            value="{{ old(\App\Models\Setting::LS_PRIVACY, $settings[\App\Models\Setting::LS_PRIVACY]) }}">
                        <trix-editor input="ls_privacy"
                            class="trix-content custom-trix-content border rounded-lg p-2 bg-white min-h-64"></trix-editor>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trix@2.0.8/dist/trix.css">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/trix@2.0.8/dist/trix.umd.min.js"></script>
@endpush
