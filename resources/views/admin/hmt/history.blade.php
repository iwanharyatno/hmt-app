@extends('layouts.admin')

@section('title', 'HMT Quiz')

@section('content')
    <div class="bg-white p-6 rounded-xl shadow-md">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4">
            <h1 class="text-2xl font-bold text-orange-600">History Volunteer HMT</h1>
            <div class="flex gap-2">
                <a href="{{ route('admin.hmt.histories.export') }}"
                    class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-center">
                    <i class="fas fa-file-csv"></i> Export Semua Jawaban (CSV)
                </a>
            </div>
        </div>

        <!-- Table (desktop) -->
        <div class="hidden sm:block overflow-hidden rounded-lg border border-gray-200">
            <table class="w-full text-sm">
                <thead class="bg-orange-100 text-orange-700">
                    <tr>
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">User</th>
                        <th class="py-3 px-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach ($participants as $i => $q)
                        <tr class="border-t hover:bg-orange-50" x-data="{ showConfirm: false }">
                            <td class="py-3 px-4">{{ $i + 1 }}</td>
                            <td class="py-3 px-4">{{ $q->user->name }}</td>
                            <td class="py-3 px-4 space-x-2">
                                <a href="{{ route('admin.hmt.histories.single-export', $q->user_id) }}"
                                    class="inline-block px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 transition">
                                    <i class="fas fa-file-csv"></i>
                                </a>
                                <a href="{{ route('admin.hmt.histories.show', $q->user_id) }}"
                                    class="inline-block px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition">
                                    <i class="fas fa-eye"></i>
                                </a>
                                {{-- <button @click="showConfirm = true"
                                    class="inline-block px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition">
                                    <i class="fas fa-trash"></i>
                                </button>

                                <!-- Konfirmasi -->
                                <div x-show="showConfirm" x-cloak
                                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40">
                                    <div class="bg-white p-6 rounded shadow max-w-sm w-full">
                                        <p class="text-gray-700 mb-4">Yakin ingin menghapus pertanyaan ini?</p>
                                        <div class="flex justify-end space-x-3">
                                            <button @click="showConfirm = false"
                                                class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                                            <form action="{{ route('admin.hmt.destroy', $q->id) }}" method="POST"
                                                onsubmit="event.preventDefault(); deleteQuestion(this);">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div> --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        async function deleteQuestion(form) {
            const res = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: new FormData(form)
            });

            const data = await res.json();
            if (res.ok && data.success) {
                alert(data.message || "Soal berhasil dihapus");
                window.location.reload();
            } else {
                alert("Gagal menghapus: " + (data.message || "Unknown error"));
            }
        }
    </script>
@endsection
