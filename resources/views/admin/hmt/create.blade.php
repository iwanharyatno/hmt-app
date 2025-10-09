@extends('layouts.admin')

@section('title', 'Tambah Soal HMT')

@section('content')
    <div class="bg-white p-6 rounded-xl shadow-md max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold text-orange-600 mb-6">Tambah Soal HMT</h1>

        <div class="my-4">
            <a href="{{ route('admin.hmt.index') }}"
                class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition text-center">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>

        <form id="hmt-form" action="{{ route('admin.hmt.store') }}" method="POST" enctype="multipart/form-data"
            class="space-y-6" x-data="{ answers: [0, 1, 2, 3, 4, 5] }">
            @csrf

            <div>
                <input type="checkbox" name="is_active" id="is_active">
                <label class="text-gray-700 mb-1" for="is_active">Aktif</label>
            </div>

            <!-- Upload Gambar Soal -->
            <div>
                <label class="block text-gray-700 mb-1">Gambar Soal</label>
                <input type="file" name="question_path"
                    class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-orange-400">
            </div>

            <!-- Upload Gambar Jawaban -->
            <div>
                <label class="block text-gray-700 mb-2">Pilihan Jawaban</label>
                <div class="grid grid-cols-2 gap-4">
                    <template x-for="(item, index) in answers" :key="index">
                        <div class="border p-3 rounded-lg">
                            <label class="block text-gray-600 mb-1">Jawaban <span x-text="index+1"></span></label>
                            <input type="file" :name="'answer_paths[]'"
                                class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-orange-400">
                            <div class="mt-2 flex items-center">
                                <input type="radio" name="correct_index" :value="index" class="mr-2">
                                <span class="text-sm text-gray-600">Tandai sebagai benar</span>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="mt-3 flex gap-2">
                    <button type="button" @click="answers.push(answers.length)"
                        class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-sm">
                        + Tambah Jawaban
                    </button>
                    <button type="button" @click="if(answers.length > 1) answers.pop()"
                        class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">
                        - Hapus Jawaban
                    </button>
                </div>
            </div>

            <!-- Tombol -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.hmt.index') }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</a>
                <button type="submit"
                    class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600">Simpan</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('hmt-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                const data = await res.json();

                if (res.ok && data.success) {
                    alert(data.message || "Soal berhasil disimpan");
                    window.location.href = "{{ route('admin.hmt.index') }}";
                } else {
                    alert("Terjadi kesalahan: " + (data.message || "Unknown error"));
                }
            } catch (err) {
                console.error(err);
                alert("Gagal submit form, coba lagi.");
            }
        });
    </script>
@endpush
