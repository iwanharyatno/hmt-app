@extends('layouts.app')

@section('title', 'Kuis Learning Style')

@section('content')
    <div class="bg-white py-14 pt-32" x-data='learningStyleQuiz({ questions: {!! $questions->toJson(JSON_HEX_APOS | JSON_HEX_QUOT) !!} })'
        x-init="init()">

        <div class="max-w-4xl mx-auto px-6 lg:px-12">

            <!-- Heading -->
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-gray-800 mb-3">Kuisioner Learning Style</h1>
                <p class="text-gray-600">Pilih jawaban yang paling menggambarkan dirimu.</p>
            </div>

            <!-- Tampilan Hasil -->
            <template x-if="showResult">
                <div class="text-center bg-white shadow-md rounded-2xl p-10 border border-gray-100">
                    <h2 class="text-2xl font-bold text-green-700 mb-4">
                        🎉 Hasilmu
                    </h2>

                    <!-- Ringkasan hasil -->
                    <p class="text-gray-800 font-semibold text-lg mb-6">
                        <template x-for="(summary, i) in result?.result.split(', ')" :key="i">
                            <span
                                class="inline-block bg-green-50 text-green-700 border border-green-200 rounded-lg px-4 py-2 mx-1">
                                <span x-text="summary"></span>
                            </span>
                        </template>
                    </p>

                    <!-- Detail breakdown -->
                    <div class="overflow-x-auto mt-6">
                        <table class="w-full border border-gray-200 rounded-lg text-left">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 border-b text-gray-700 font-semibold">Dimensi</th>
                                    <th class="px-4 py-2 border-b text-gray-700 font-semibold">Skor</th>
                                    <th class="px-4 py-2 border-b text-gray-700 font-semibold">Arah</th>
                                    <th class="px-4 py-2 border-b text-gray-700 font-semibold">Intensitas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(d, i) in result?.details" :key="i">
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 border-b text-gray-800" x-text="d.dimension"></td>
                                        <td class="px-4 py-2 border-b text-gray-800 text-center" x-text="d.score"></td>
                                        <td class="px-4 py-2 border-b text-gray-800 text-center" x-text="d.direction"></td>
                                        <td class="px-4 py-2 border-b text-gray-800 text-center" x-text="d.intensity"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Tombol -->
                    <div class="mt-8">
                        <button @click="window.location.href='{{ route('user.dashboard') }}'"
                            class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                            Kembali ke Dashboard
                        </button>
                    </div>
                </div>
            </template>

            <!-- Pertanyaan -->
            <template x-if="!showResult">
                <div>
                    <template x-for="(q, qi) in paginatedQuestions" :key="q.id">
                        <div class="bg-white rounded-2xl shadow-md p-6 mb-8 border border-gray-100">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="fas fa-question-circle text-indigo-600"></i>
                                <span x-text="`Pertanyaan ${startIndex + qi + 1}`"></span>
                            </h2>
                            <p class="text-gray-700 mb-6" x-text="q.question"></p>

                            <div class="space-y-3">
                                <template x-for="(ans, ai) in q.answers" :key="ai">
                                    <button @click="selectAnswer(qi, ai, ans.point)" :class="answerClass(qi, ai)"
                                        class="w-full text-left px-4 py-3 rounded-lg flex items-center gap-3 transition">
                                        <i class="fas fa-circle text-indigo-400"></i>
                                        <span x-text="ans.text"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Tombol Navigasi -->
                    <div class="flex justify-between mt-8">
                        <button @click="prevPage" :disabled="page === 1" class="px-5 py-2 rounded-lg transition"
                            :class="page === 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' :
                                'bg-gray-200 text-gray-700 hover:bg-gray-300'">
                            <i class="fas fa-arrow-left"></i> Sebelumnya
                        </button>

                        <template x-if="endIndex < questions.length">
                            <button @click="nextPage" :disabled="!allAnsweredOnPage"
                                class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition disabled:bg-gray-300 disabled:cursor-not-allowed">
                                Selanjutnya <i class="fas fa-arrow-right"></i>
                            </button>
                        </template>

                        <template x-if="endIndex >= questions.length">
                            <button @click="submitQuiz" :disabled="!allAnsweredOnPage || submitting"
                                class="px-5 py-2 flex items-center justify-center gap-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition disabled:bg-gray-300 disabled:cursor-not-allowed">
                                <template x-if="!submitting">
                                    <span>
                                        Selesai <i class="fas fa-check ml-1"></i>
                                    </span>
                                </template>
                                <template x-if="submitting">
                                    <span class="flex items-center gap-2">
                                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                        </svg>
                                        Mengirim...
                                    </span>
                                </template>
                            </button>
                        </template>
                    </div>
                </div>
            </template>

        </div>
    </div>

    @push('scripts')
        <script>
            function learningStyleQuiz({
                questions
            }) {
                return {
                    questions: questions || [],
                    page: 1,
                    perPage: 11,
                    answers: {},
                    submitting: false,
                    showResult: false,
                    result: null,
                    init() {
                        this.questions = this.questions.map(q => ({
                            ...q,
                            answers: typeof q.answers === 'string' ? JSON.parse(q.answers) : q.answers
                        }));
                    },
                    get startIndex() {
                        return (this.page - 1) * this.perPage
                    },
                    get endIndex() {
                        return this.startIndex + this.perPage
                    },
                    get paginatedQuestions() {
                        return this.questions.slice(this.startIndex, this.endIndex)
                    },
                    get allAnsweredOnPage() {
                        // pastikan semua pertanyaan di halaman ini sudah dijawab
                        return this.paginatedQuestions.every((q, qi) => {
                            const globalIndex = this.startIndex + qi;
                            return this.answers[globalIndex] !== undefined;
                        });
                    },
                    nextPage() {
                        if (this.endIndex < this.questions.length && this.allAnsweredOnPage) this.page++;
                    },
                    prevPage() {
                        if (this.page > 1) this.page--;
                    },
                    selectAnswer(qi, aIndex, point) {
                        const globalIndex = this.startIndex + qi; // index absolut pertanyaan
                        this.answers[globalIndex] = {
                            index: aIndex,
                            point
                        };
                        console.log(JSON.parse(JSON.stringify(this.answers)));
                    },
                    answerClass(qi, aIndex) {
                        const globalIndex = this.startIndex + qi;
                        return this.answers[globalIndex]?.index === aIndex ?
                            'bg-indigo-100 border border-indigo-300 text-indigo-700' :
                            'bg-gray-100 hover:bg-indigo-50 text-gray-700';
                    },
                    async submitQuiz() {
                        if (!this.allAnsweredOnPage || this.submitting) return;
                        this.submitting = true;

                        const payload = {
                            answers: this.answers,
                            completed_at: new Date().toISOString()
                        };

                        try {
                            const res = await fetch('{{ route('user.learning-style.submit') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify(payload)
                            });

                            const data = await res.json();
                            console.log(data);

                            if (data.success && data.result) {
                                this.result = data.result;
                                this.showResult = true;
                            } else {
                                throw new Error(data.message || 'Submission failed');
                            }

                        } catch (err) {
                            console.error(err);
                            Swal.fire({
                                title: 'Terjadi Kesalahan',
                                text: 'Gagal menyimpan jawaban. Coba lagi nanti.',
                                icon: 'error',
                                confirmButtonColor: '#ef4444',
                            });
                        } finally {
                            this.submitting = false;
                        }
                    }
                };
            }
        </script>
    @endpush
@endsection
