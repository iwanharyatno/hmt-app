@extends('layouts.admin')

@section('title', 'Editor Pertanyaan Learning Style')

@section('content')
    <div class="bg-white p-6 rounded-xl shadow-md max-w-4xl mx-auto" x-data="questionEditor()" x-init="init()">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-orange-600">Editor Pertanyaan Learning Style</h1>
            <button @click="addQuestion()"
                class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition">
                + Tambah Pertanyaan
            </button>
        </div>

        <template x-for="(q, index) in questions" :key="q.local_id">
            <div class="border border-gray-200 rounded-lg p-5 mb-5 shadow-sm bg-orange-50 relative">
                <div class="absolute top-3 right-3 flex gap-2 items-center">
                    <span class="text-sm text-gray-500" x-text="saving[q.local_id]"></span>
                    <button @click="deleteQuestion(q)" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i>
                    </button>
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" x-model="q.is_active" @change="saveQuestion(q)"
                            class="rounded border-gray-300 text-orange-500 focus:ring-orange-400">
                        <span x-text="q.is_active ? 'Aktif' : 'Nonaktif'"></span>
                    </label>
                </div>

                <div class="flex items-center justify-between mb-3">
                    <label class="block font-semibold text-gray-700">Pertanyaan</label>
                </div>

                <textarea x-model="q.question" @change="saveQuestion(q)"
                    class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-orange-400" rows="2"></textarea>

                <div class="mt-4">
                    <label class="block font-semibold text-gray-700 mb-2">Jawaban & Poin</label>
                    <template x-for="(ans, ai) in q.answers" :key="ai">
                        <div class="flex items-center gap-3 mb-2">
                            <input type="text" x-model="ans.text" @change="saveQuestion(q)"
                                class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-orange-400"
                                :placeholder="'Jawaban ' + (ai + 1)">
                            <input type="number" x-model.number="ans.point" @change="saveQuestion(q)"
                                class="w-24 border rounded-lg p-2 focus:ring-2 focus:ring-orange-400" placeholder="Poin">
                        </div>
                    </template>
                </div>
            </div>
        </template>

        <div x-show="questions.length === 0" class="text-gray-500 italic text-center py-10">
            Belum ada pertanyaan. Klik tombol <b>Tambah Pertanyaan</b> di atas.
        </div>
    </div>

    <script>
        function questionEditor() {
            return {
                questions: [],
                saving: {},

                async init() {
                    const res = await fetch('{{ route('admin.learning-style.all') }}');
                    this.questions = await res.json();

                    // Pastikan q.is_active adalah boolean murni
                    this.questions.forEach(q => {
                        q.local_id = q.id || Date.now() + Math.random();
                        q.is_active = Boolean(Number(q.is_active)); // konversi '1'/'0' ke true/false
                    });
                },

                addQuestion() {
                    const q = {
                        id: null,
                        local_id: Date.now() + Math.random(),
                        question: '',
                        is_active: true,
                        answers: [{
                                text: '',
                                point: 0
                            },
                            {
                                text: '',
                                point: 0
                            }
                        ],
                    };
                    this.questions.push(q);
                },

                async saveQuestion(q) {
                    this.saving[q.local_id] = '💾 Saving...';
                    try {
                        const res = await fetch('{{ route('admin.learning-style.save') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(q)
                        });
                        const data = await res.json();
                        if (data.success) {
                            q.id = data.id;
                            if (data.is_partial) {
                                this.saving[q.local_id] = 'Saved (incomplete)';
                            } else {
                                this.saving[q.local_id] = 'Saved';
                            }
                        } else {
                            this.saving[q.local_id] = 'Error';
                        }
                    } catch (e) {
                        this.saving[q.local_id] = 'Failed';
                    }
                },

                async deleteQuestion(q) {
                    if (!q.id) {
                        this.questions = this.questions.filter(x => x.local_id !== q.local_id);
                        return;
                    }

                    if (!confirm('Yakin ingin menghapus pertanyaan ini?')) return;
                    try {
                        await fetch(`/admin/learning-style/delete/${q.id}`, {
                            method: 'POST',
                            body: JSON.stringify({
                                '_method': 'DELETE'
                            }),
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        });
                        this.questions = this.questions.filter(x => x.local_id !== q.local_id);
                    } catch (e) {
                        alert('Gagal menghapus pertanyaan');
                    }
                },
            };
        }
    </script>
@endsection
