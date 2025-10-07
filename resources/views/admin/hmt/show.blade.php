@extends('layouts.admin')

@section('title', 'HMT Quiz')

@section('content')
    <div class="bg-white p-6 rounded-xl shadow-md">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4">
            <h1 class="text-2xl font-bold text-orange-600">History Volunteer HMT</h1>
            <div class="flex gap-2">
                <a href="{{ route('admin.hmt.histories.single-export', $user->id) }}"
                    class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-center">
                    <i class="fas fa-file-csv"></i> Export User Ini
                </a>
            </div>
        </div>
        <div class="mb-4">
            <table class="border border-gray-200 w-full">
                <tr>
                    <th class="border border-gray-200 text-left p-4">Nama Volunteer</th>
                    <td class="border border-gray-200 p-4">{{ $user->name }}</td>
                </tr>
            </table>
        </div>
        <div class="hidden sm:block overflow-hidden rounded-lg border border-gray-200">
            <table class="w-full text-sm">
                <thead class="bg-orange-100 text-orange-700">
                    <tr>
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Pertanyaan</th>
                        <th class="py-3 px-4 text-left">Jawaban</th>
                        <th class="py-3 px-4 text-left"><em>Timestamp</em></th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach ($history as $i => $q)
                        <tr class="border-t hover:bg-orange-50" x-data="{ showConfirm: false }">
                            <td class="py-3 px-4">{{ $i + 1 }}</td>
                            <td class="py-3 px-4">
                                @if ($q->question->question_path)
                                    <img src="{{ Storage::url($q->question->question_path) }}" alt="Soal"
                                        class="h-12 rounded border object-cover">
                                @else
                                    <span class="text-gray-400 italic">Tidak ada gambar</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 space-x-2">
                                <p>
                                    @if ($q->question->question_path)
                                        <img src="{{ Storage::url($q->question->answer_paths[$q->answer_index]) }}"
                                            alt="Soal" class="h-12 rounded border object-cover">
                                    @else
                                        <span class="text-gray-400 italic">Tidak ada gambar</span>
                                    @endif
                                </p>
                                @php
                                    $jawaban = $q->answer_index == $q->question->correct_index ? 'BENAR' : 'SALAH';
                                @endphp
                                <p class="{{ $jawaban == 'BENAR' ? 'text-green-600' : 'text-yellow-600' }}">
                                    {{-- {{ $q->answer_index }} --}}
                                    <span>({{ $jawaban }})</span>
                                </p>
                            </td>
                            <td class="py-3 px-4 space-x-2">
                                {{ $q->answered_at->format('H:i:s') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
