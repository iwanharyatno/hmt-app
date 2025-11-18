@extends('layouts.admin')

@section('title', 'Edit Soal HMT')

@section('content')
    <div class="bg-white p-6 rounded-xl shadow-md max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold text-orange-600 mb-6">Edit Soal HMT</h1>

        <div class="my-4">
            <a href="{{ route('admin.hmt.index') }}"
                class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition text-center">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>

        <form id="hmt-form" action="{{ route('admin.hmt.update', $question->id) }}" method="POST"
            enctype="multipart/form-data" class="space-y-6" x-data="{ answers: @js($question->answer_paths ?? []), correctIndex: @js($question->correct_index) }">
            @csrf
            @method('PUT')

            <div>
                <input type="checkbox" name="is_active" @if ($question->is_active) checked @endif id="is_active">
                <label class="text-gray-700 mb-1" for="is_active">Aktif</label>
            </div>

            <!-- Contoh / Solusi -->
            <div x-data="{ showSolution: !!Number('{{ $question->is_example }}') }" class="space-y-3">

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_example" id="is_example" x-model="showSolution">
                    <label for="is_example" class="text-gray-700">Tampilkan Sebagai Contoh</label>
                </div>

                <div x-show="showSolution" x-transition>
                    <label class="block text-gray-700 mb-1">Deskripsi Solusi</label>

                    <input id="solution_description" type="hidden" name="solution_description"
                        value="{{ $question->solution_description ?? '' }}">

                    <trix-editor input="solution_description"
                        class="trix-content border rounded-lg p-2 bg-white min-h-40"></trix-editor>
                </div>
            </div>

            <!-- Upload Gambar Soal -->
            <div x-data="{ questionPreview: null }">
                <label class="block text-gray-700 mb-1">Gambar Soal</label>
                @if ($question->question_path)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $question->question_path) }}" alt="Soal"
                            class="w-40 h-auto rounded">
                    </div>
                @endif
                <input type="file" name="question_path"
                    @change="questionPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                    class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-orange-400">
                <template x-if="questionPreview">
                    <img :src="questionPreview" class="mt-3 rounded-lg border w-40 object-contain">
                </template>
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti gambar soal</p>
            </div>

            <!-- Upload Gambar Jawaban -->
            <div>
                <label class="block text-gray-700 mb-2">Pilihan Jawaban</label>
                <div class="grid grid-cols-2 gap-4">
                    <template x-for="(item, index) in answers" :key="index">
                        <div class="border p-3 rounded-lg" x-data="{ preview: null }">
                            <label class="block text-gray-600 mb-1">
                                Jawaban <span x-text="index + 1"></span>
                            </label>

                            <!-- Preview jawaban lama -->
                            <div class="mb-2" x-show="item">
                                <img :src="item ? '{{ asset('storage') }}/' + item : ''" alt="Jawaban"
                                    class="w-24 h-auto rounded">
                            </div>

                            <!-- Input file -->
                            <input type="file" :name="'answer_paths[' + index + ']'"
                                @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                                class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-orange-400">

                            <template x-if="preview">
                                <img :src="preview" class="mt-2 rounded-lg border w-24 object-contain">
                            </template>

                            <!-- Pilih jawaban benar -->
                            <div class="mt-2 flex items-center">
                                <input type="radio" name="correct_index" :value="index" x-model="correctIndex"
                                    class="mr-2">
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
                <button type="submit" id="submit-btn"
                    class="px-4 py-2 bg-orange-500 text-white disabled:pointer-events-none rounded hover:bg-orange-600 flex items-center justify-center gap-2">
                    <span id="submit-text">Update</span>
                    <svg id="submit-spinner" class="animate-spin h-5 w-5 text-white hidden"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                </button>
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
            const submitBtn = document.getElementById('submit-btn');
            const submitText = document.getElementById('submit-text');
            const spinner = document.getElementById('submit-spinner');

            // Loading state
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
            submitText.textContent = 'Menyimpan...';
            spinner.classList.remove('hidden');

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
                    alert(data.message || "Soal berhasil diperbarui");
                    window.location.href = @json(route('admin.hmt.index'));
                } else {
                    alert("Terjadi kesalahan: " + (data.message || "Unknown error"));
                }
            } catch (err) {
                console.error(err);
                alert("Gagal submit form, coba lagi.");
            } finally {
                // Reset state
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
                submitText.textContent = 'Update';
                spinner.classList.add('hidden');
            }
        });
    </script>
@endpush
