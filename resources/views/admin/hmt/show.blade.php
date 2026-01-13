@extends('layouts.admin')

@section('title', 'HMT Quiz')

@section('content')
    <div class="bg-white p-6 rounded-xl shadow-md">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4">
            <h1 class="text-2xl font-bold text-orange-600">History Volunteer HMT</h1>
            <div class="flex gap-2">
                <a href="{{ route('admin.hmt.histories.single-export', $session->id) }}"
                    class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-center">
                    <i class="fas fa-file-csv"></i> Export Session Ini
                </a>
            </div>
        </div>

        <div class="my-4">
            <a href="{{ route('admin.hmt.history') }}"
                class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition text-center">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Info Session -->
        <div class="overflow-x-auto mb-4">
            <table class="border border-gray-200 w-full text-sm">
                <tr>
                    <th class="border border-gray-200 text-left p-4 w-1/3">Nama Volunteer</th>
                    <td class="border border-gray-200 p-4">{{ $session->user->name }}</td>
                </tr>
                <tr>
                    <th class="border border-gray-200 text-left p-4 w-1/3">Percobaan</th>
                    <td class="border border-gray-200 p-4">{{ $session->attempts }}</td>
                </tr>
                <tr>
                    <th class="border border-gray-200 text-left p-4">Tanggal Mulai</th>
                    <td class="border border-gray-200 p-4">
                        {{ $session->started_at ? $session->started_at->format('d M Y, H:i:s.v') : '-' }}
                    </td>
                </tr>
                <tr>
                    <th class="border border-gray-200 text-left p-4">Tanggal Selesai</th>
                    <td class="border border-gray-200 p-4">
                        {{ $session->finished_at ? $session->finished_at->format('d M Y, H:i:s.v') : '-' }}
                    </td>
                </tr>
                <tr>
                    <th class="border border-gray-200 text-left p-4">Total Soal Dijawab</th>
                    <td class="border border-gray-200 p-4">{{ $session->histories->count() }}</td>
                </tr>
            </table>
        </div>

        <!-- Tabel Jawaban -->
        <div class="hidden sm:block overflow-hidden rounded-lg border border-gray-200">
            <table class="w-full text-sm">
                <thead class="bg-orange-100 text-orange-700">
                    <tr>
                        <th class="py-3 px-4 text-left">#</th>
                        <th class="py-3 px-4 text-left">Kode Pertanyaan</th>
                        <th class="py-3 px-4 text-left">Pertanyaan</th>
                        <th class="py-3 px-4 text-left">Jawaban</th>
                        <th class="py-3 px-4 text-left"><em>Timestamp</em></th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach ($session->histories as $i => $q)
                        <tr class="border-t hover:bg-orange-50">
                            <td class="py-3 px-4">{{ $i + 1 }}</td>
                            <td class="py-3 px-4">{{ $q->question->id }}</td>

                            <!-- Gambar Soal -->
                            <td class="py-3 px-4">
                                @if ($q->question->question_path)
                                    <img src="{{ Storage::url($q->question->question_path) }}" alt="Soal"
                                        class="h-12 rounded border object-cover">
                                @else
                                    <span class="text-gray-400 italic">Tidak ada gambar</span>
                                @endif
                            </td>

                            <!-- Jawaban -->
                            <td class="py-3 px-4">
                                @if (isset($q->question->answer_paths[$q->answer_index]))
                                    <img src="{{ Storage::url($q->question->answer_paths[$q->answer_index]) }}"
                                        alt="Jawaban" class="h-12 rounded border object-cover">
                                @else
                                    <span class="text-gray-400 italic">Tidak ada gambar</span>
                                @endif
                                @php
                                    $jawaban = $q->answer_index && $q->answer_index == $q->question->correct_index ? 'BENAR' : 'SALAH';
                                @endphp
                                <p class="{{ $jawaban == 'BENAR' ? 'text-green-600' : 'text-yellow-600' }}">
                                    {{-- {{ $q->answer_index }} --}}
                                    <span>({{ $jawaban }})</span>
                                </p>
                            </td>

                            <!-- Timestamp -->
                            <td class="py-3 px-4">
                                {{ $q->answered_at ? $q->answered_at->format('H:i:s.v') : '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
