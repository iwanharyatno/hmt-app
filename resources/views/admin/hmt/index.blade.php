@extends('layouts.admin')

@section('title', 'HMT Quiz')

@section('content')
    <div class="bg-white p-6 rounded-xl shadow-md">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4">
            <h1 class="text-2xl font-bold text-orange-600">Daftar Pertanyaan HMT</h1>
            <div class="flex gap-2">
                <a href="{{ route('admin.hmt.histories.export') }}"
                    class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-center">
                    <i class="fas fa-file-csv"></i> Export Jawaban (CSV)
                </a>
                <a href="{{ route('admin.hmt.create') }}"
                    class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition text-center">
                    <i class="fas fa-plus"></i> Tambah Pertanyaan
                </a>
            </div>
        </div>

        <!-- Table (desktop) -->
        <div class="hidden sm:block overflow-hidden rounded-lg border border-gray-200">
            <table class="w-full text-sm">
                <thead class="bg-orange-100 text-orange-700">
                    <tr>
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Pertanyaan</th>
                        <th class="py-3 px-4 text-left">Gambar</th>
                        <th class="py-3 px-4 text-left">Aktif</th>
                        <th class="py-3 px-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach ($questions as $i => $q)
                        <tr class="border-t hover:bg-orange-50" x-data="{ showConfirm: false }">
                            <td class="py-3 px-4">{{ $i + 1 }}</td>
                            <td class="py-3 px-4">Soal #{{ $i + 1 }} ({{ basename($q->question_path) }})</td>
                            <td class="py-3 px-4">
                                @if ($q->question_path)
                                    <img src="{{ Storage::url($q->question_path) }}" alt="Soal"
                                        class="h-12 rounded border object-cover">
                                @else
                                    <span class="text-gray-400 italic">Tidak ada gambar</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 space-x-2">
                                {{ $q->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </td>
                            <td class="py-3 px-4 space-x-2">
                                <a href="{{ route('admin.hmt.edit', $q->id) }}"
                                    class="inline-block px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button @click="showConfirm = true"
                                    class="inline-block px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition">
                                    <i class="fas fa-trash"></i>
                                </button>

                                <!-- Konfirmasi -->
                                <div x-show="showConfirm" x-cloak
                                    class="fixed inset-0 flex items-center justify-center bg-black/40">
                                    <div class="bg-white p-6 rounded shadow max-w-sm w-full">
                                        <p class="text-gray-700 mb-4">Yakin ingin menghapus pertanyaan ini?</p>
                                        <div class="flex justify-end space-x-3">
                                            <button @click="showConfirm = false"
                                                class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                                            <form action="{{ route('admin.hmt.destroy', $q->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Card View (mobile) -->
        <div class="space-y-4 sm:hidden">
            @foreach ($questions as $i => $q)
                <div class="border rounded-lg p-4 shadow hover:bg-orange-50" x-data="{ showConfirm: false }">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-semibold text-orange-600">Soal #{{ $i + 1 }}</span>
                        <div class="space-x-2">
                            <a href="{{ route('admin.hmt.edit', $q->id) }}"
                                class="inline-block px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition text-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button @click="showConfirm = true"
                                class="inline-block px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition text-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <p class="text-gray-700 mb-3">File: {{ basename($q->question_path) }}</p>
                    @if ($q->question_path)
                        <img src="{{ Storage::url($q->question_path) }}" alt="Soal"
                            class="h-20 rounded border object-cover">
                    @endif

                    <!-- Konfirmasi -->
                    <div x-show="showConfirm" x-cloak
                        class="fixed inset-0 flex items-center justify-center bg-black/40">
                        <div class="bg-white p-6 rounded shadow max-w-sm w-full">
                            <p class="text-gray-700 mb-4">Yakin ingin menghapus pertanyaan ini?</p>
                            <div class="flex justify-end space-x-3">
                                <button @click="showConfirm = false"
                                    class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                                <form action="{{ route('admin.hmt.destroy', $q->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
