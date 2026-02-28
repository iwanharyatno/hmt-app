@extends('layouts.app')

@section('title', 'Kuis Learning Style')

@section('content')
    <div class="bg-white py-14 pt-32"
        x-data='learningStyleQuiz({
            questions: {!! $questions->toJson(JSON_HEX_APOS | JSON_HEX_QUOT) !!},
            lsDescription: `{!! addslashes($settings[\App\Models\Setting::LS_DESCRIPTION] ?? '') !!}`,
            lsPrivacy: `{!! addslashes($settings[\App\Models\Setting::LS_PRIVACY] ?? '') !!}`
        })' x-init="init()">

        <div class="max-w-4xl mx-auto px-6 lg:px-12">

            <!-- =========================== -->
            <!-- PREFACE / INTRODUCTION PAGE -->
            <!-- =========================== -->
            <template x-if="showPreface">
                <div class="bg-white rounded-2xl shadow-md p-8 border border-gray-100">
                    <h1 class="text-3xl font-bold text-orange-700 mb-4 text-center">
                        Asessmen Profil Gaya Belajar Felder-Silverman Learning Style Model (FSLSM)
                    </h1>

                    <!-- Petunjuk / Deskripsi -->
                    <div class="text-gray-700 text-left prose max-w-none mb-6 custom-trix-content" x-html="lsDescription"></div>

                    <!-- Checkbox & Privacy Modal Trigger -->
                    <div class="flex flex-col gap-4 mt-6">
                        <div class="flex gap-2">
                            <input type="checkbox" id="agreePrivacy" x-model="privacyAgreed"
                                class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                            <label for="agreePrivacy" class="text-gray-700 text-sm">
                                Saya mengerti instruksi di atas. Saya bersedia mengisi kuisioner ini dengan jujur dan spontan sesuai keadaan saya yang sebenarnya.
                                {{-- <button type="button" @click="showPrivacy = true"
                                    class="text-orange-600 font-medium underline hover:text-orange-800">
                                    Kebijakan Privasi
                                </button> --}}
                            </label>
                        </div>

                        <button @click="startQuiz()" :disabled="!privacyAgreed"
                            :class="privacyAgreed
                                ? 'bg-orange-600 hover:bg-orange-700'
                                : 'bg-gray-300 cursor-not-allowed'"
                            class="text-white px-6 py-2 rounded-lg transition font-medium">
                            Mulai Kuis
                        </button>
                    </div>

                    <!-- Modal Privacy Policy -->
                    <div x-show="showPrivacy" x-cloak
                        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
                        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 relative overflow-hidden">
                            <div class="p-6 max-h-[80vh] overflow-y-auto text-left">
                                <h2 class="text-xl font-semibold text-orange-700 mb-4">Kebijakan Privasi</h2>
                                <div class="prose max-w-none text-gray-700 custom-trix-content" x-html="lsPrivacy"></div>
                            </div>
                            <div class="flex justify-end border-t bg-gray-50 p-4">
                                <button @click="showPrivacy = false"
                                    class="px-5 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- =========================== -->
            <!-- QUIZ QUESTIONS -->
            <!-- =========================== -->
            <template x-if="!showPreface && !showResult">
                <div>
                    <template x-for="(q, qi) in paginatedQuestions" :key="q.id">
                        <div class="bg-white rounded-2xl shadow-md p-6 mb-8 border border-gray-100">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="fas fa-question-circle text-orange-600"></i>
                                <span x-text="`Pertanyaan ${startIndex + qi + 1}`"></span>
                            </h2>
                            <p class="text-gray-700 mb-6" x-text="q.question"></p>

                            <div class="space-y-3">
                                <template x-for="(ans, ai) in q.answers" :key="ai">
                                    <button @click="selectAnswer(qi, ai, ans.point)" :class="answerClass(qi, ai)"
                                        class="w-full text-left px-4 py-3 rounded-lg flex items-center gap-3 transition">
                                        <i class="fas fa-circle text-orange-400"></i>
                                        <span x-text="ans.text"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Navigasi -->
                    <div class="flex justify-between mt-8">
                        <button @click="prevPage" :disabled="page === 1" class="px-5 py-2 rounded-lg transition"
                            :class="page === 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' :
                                'bg-gray-200 text-gray-700 hover:bg-gray-300'">
                            <i class="fas fa-arrow-left"></i> Sebelumnya
                        </button>

                        <template x-if="endIndex < questions.length">
                            <button @click="nextPage" :disabled="!allAnsweredOnPage"
                                class="px-5 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition disabled:bg-gray-300 disabled:cursor-not-allowed">
                                Selanjutnya <i class="fas fa-arrow-right"></i>
                            </button>
                        </template>

                        <template x-if="endIndex >= questions.length">
                            <button @click="submitQuiz" :disabled="!allAnsweredOnPage || submitting"
                                class="px-5 py-2 flex items-center justify-center gap-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition disabled:bg-gray-300 disabled:cursor-not-allowed">
                                <template x-if="!submitting">
                                    <span>Selesai <i class="fas fa-check ml-1"></i></span>
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

            <!-- =========================== -->
            <!-- RESULT PAGE -->
            <!-- =========================== -->
            <template x-if="showResult">
                <div class="text-center bg-white shadow-md rounded-2xl p-10 border border-gray-100">
                    <h2 class="text-2xl font-bold text-green-700 mb-4">🎉 Hasilmu</h2>

                    <!-- Ringkasan -->
                    <p class="text-gray-800 font-semibold text-lg mb-6">
                        <template x-for="(summary, i) in result?.result.split(', ')" :key="i">
                            <span
                                class="inline-block bg-green-50 text-green-700 border border-green-200 rounded-lg px-4 py-2 mx-1">
                                <span x-text="summary"></span>
                            </span>
                        </template>
                    </p>

                    <!-- Tabel detail -->
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

                    <!-- Tombol kembali -->
                    <div class="mt-8">
                        <button @click="window.location.href='{{ route('user.dashboard') }}'"
                            class="bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700 transition">
                            Kembali ke Dashboard
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    @push('scripts')
        <script>
            function learningStyleQuiz({ questions, lsDescription, lsPrivacy }) {
                return {
                    questions: questions || [],
                    lsDescription,
                    lsPrivacy,
                    showPreface: true,
                    showPrivacy: false,
                    privacyAgreed: false,
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
                    startQuiz() {
                        if (this.privacyAgreed) this.showPreface = false;
                    },
                    get startIndex() { return (this.page - 1) * this.perPage },
                    get endIndex() { return this.startIndex + this.perPage },
                    get paginatedQuestions() { return this.questions.slice(this.startIndex, this.endIndex) },
                    get allAnsweredOnPage() {
                        return this.paginatedQuestions.every((q, qi) => {
                            const globalIndex = this.startIndex + qi;
                            return this.answers[globalIndex] !== undefined;
                        });
                    },
                    nextPage() { if (this.endIndex < this.questions.length && this.allAnsweredOnPage) this.page++; },
                    prevPage() { if (this.page > 1) this.page--; },
                    selectAnswer(qi, aIndex, point) {
                        const globalIndex = this.startIndex + qi;
                        this.answers[globalIndex] = { index: aIndex, point };
                    },
                    answerClass(qi, aIndex) {
                        const globalIndex = this.startIndex + qi;
                        return this.answers[globalIndex]?.index === aIndex
                            ? 'bg-orange-100 border border-orange-300 text-orange-700'
                            : 'bg-gray-100 hover:bg-orange-50 text-gray-700';
                    },
                    async submitQuiz() {
                        if (!this.allAnsweredOnPage || this.submitting) return;
                        this.submitting = true;
                        const payload = { answers: this.answers, completed_at: new Date().toISOString() };

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
                            if (data.success && data.result) {
                                this.result = data.result;
                                this.showResult = true;
                            } else {
                                throw new Error(data.message || 'Submission failed');
                            }
                        } catch (err) {
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
