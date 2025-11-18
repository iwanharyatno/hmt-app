@extends('layouts.plain')

@section('title', 'Kuis HMT')

@section('content')
    <div class="h-full py-4 flex absolute top-0 left-0 w-full" x-data="quiz({{ $questions->toJson() }}, {{ json_encode($settings[\App\Models\Setting::HMT_DESCRIPTION]) }}, {{ json_encode($settings[\App\Models\Setting::HMT_PRIVACY]) }})">

        <div class="max-w-4xl grow mx-auto px-6 lg:px-12">

            <!-- ===================== -->
            <!-- STAGE 1: PREFACE -->
            <!-- ===================== -->
            <template x-if="stage === 0">
                <div class="shadow-xl p-6 rounded-xl border border-orange-200 bg-white space-y-5">
                    <h1 class="text-2xl font-bold text-center text-orange-600 mb-3">Kuis HMT (Hagen Matrices Test)</h1>
                    <div class="custom-trix-content prose max-w-none text-gray-700" x-html="description"></div>

                    <!-- Privacy Agreement -->
                    <div class="mt-6">
                        <button type="button" @click="showPrivacy = true"
                            class="text-orange-600 underline text-sm hover:text-orange-700 transition">
                            Lihat Kebijakan Privasi
                        </button>

                        <div class="flex items-center mt-3 gap-2">
                            <input type="checkbox" id="agreePrivacy" x-model="privacyAgreed"
                                class="rounded text-orange-600">
                            <label for="agreePrivacy" class="text-sm text-gray-700">
                                Saya telah membaca dan menyetujui <span class="text-orange-600 font-medium">Kebijakan
                                    Privasi</span>.
                            </label>
                        </div>
                    </div>

                    <!-- Next Button -->
                    <div class="text-center pt-4">
                        <button @click="nextStage()" :disabled="!privacyAgreed"
                            :class="[
                                'inline-block px-5 py-2 rounded font-semibold transition',
                                privacyAgreed ? 'bg-orange-500 hover:bg-orange-600 text-white cursor-pointer' :
                                'bg-gray-300 text-gray-500 cursor-not-allowed'
                            ]">
                            <i class="fas fa-arrow-right"></i> Lanjut
                        </button>
                    </div>
                </div>
            </template>

            @foreach ($examples as $example)
                <template x-if="stage === Number('{{ $loop->index + 1 }}')">
                    <!-- Wrap 1 scope Alpine untuk demoSelected + openSolution -->
                    <div x-data="{
                        demoSelected: null,
                        openSolution: false,
                        correctIndex: {{ $example['correct_index'] }}
                    }"
                        class="shadow-xl p-6 rounded-xl border border-orange-200 bg-white space-y-5">

                        <h2 class="text-xl font-semibold text-center text-orange-600">
                            Contoh Soal {{ $loop->index + 1 }}
                        </h2>
                        <p class="text-center text-sm text-gray-600">
                            Coba pilih jawaban yang berbeda dari sebelumnya.
                        </p>

                        <div class="flex justify-center mb-6">
                            <img src="{{ $example['question_path'] }}" alt="Contoh Soal HMT"
                                class="rounded-lg shadow max-h-64">
                        </div>

                        <!-- GRID JAWABAN -->
                        <div class="grid md:grid-cols-6 grid-cols-3 gap-3">
                            @foreach ($example['answer_paths'] as $answer)
                                <button type="button" @click="demoSelected = {{ $loop->index }}"
                                    :class="[
                                        'p-2 rounded-lg transition flex items-center justify-center border',
                                    
                                        // Kalau user memilih jawaban / saat show jawaban benar
                                        openSolution && correctIndex === {{ $loop->index }} ?
                                        'bg-green-200 ring-2 ring-green-500' :
                                        demoSelected === {{ $loop->index }} ?
                                        'bg-orange-200 ring-2 ring-orange-500' :
                                        'bg-white hover:bg-orange-50 border-gray-200'
                                    ]">
                                    <img src="{{ $answer }}" alt="Jawaban Demo" class="max-h-20 object-contain">
                                </button>
                            @endforeach
                        </div>

                        <!-- ACCORDION SOLUSI -->
                        <div class="pt-6">
                            <button @click="openSolution = !openSolution"
                                class="text-orange-600 underline text-sm font-semibold">
                                <span x-show="!openSolution">Tampilkan Solusi</span>
                                <span x-show="openSolution">Sembunyikan Solusi</span>
                            </button>

                            <div x-show="openSolution" x-transition
                                class="mt-4 p-4 bg-orange-50 border border-orange-200 rounded-lg space-y-4">
                                <h3 class="font-semibold text-orange-700">Solusi:</h3>

                                <img src="{{ $example['answer_paths'][$example['correct_index']] }}"
                                    class="max-w-40 rounded shadow w-full" alt="Solusi Benar">

                                <div class="prose max-w-none text-sm" x-html="`{!! str_replace('`', '\\`', $example['solution_description']) !!}`">
                                </div>
                            </div>
                        </div>

                        <!-- BUTTON NEXT / START -->
                        @if ($loop->last)
                            <div class="text-center pt-6">
                                <button @click="startQuiz()" :disabled="loading"
                                    :class="[
                                        'inline-block px-5 py-2 rounded font-semibold transition',
                                        loading ? 'bg-gray-300 text-gray-500 cursor-not-allowed' :
                                        'bg-orange-500 hover:bg-orange-600 text-white cursor-pointer'
                                    ]">
                                    <i class="fas fa-play-circle"></i> Mulai Tes
                                </button>
                            </div>
                        @else
                            <div class="text-center pt-6">
                                <button @click="nextStage()"
                                    class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded transition">
                                    <i class="fas fa-arrow-right"></i> Lanjut ke Contoh {{ $loop->index + 2 }}
                                </button>
                            </div>
                        @endif

                    </div>
                </template>
            @endforeach

            <!-- ======================= -->
            <!-- STAGE 4: PERTANYAAN ASLI -->
            <!-- ======================= -->
            <template x-if="stage === Number('{{ count($examples) + 1 }}') && currentQuestion">
                <div class="bg-white rounded-2xl shadow-md p-6 mb-8">
                    <h2 class="text-lg text-center font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-puzzle-piece text-orange-600"></i>
                        Pertanyaan <span x-text="currentIndex + 1"></span> dari <span x-text="questions.length"></span>
                    </h2>

                    <div class="flex justify-center mb-6">
                        <img :src="currentQuestion.question_path" alt="Soal HMT" class="rounded-lg shadow max-h-80">
                    </div>

                    <div class="grid md:grid-cols-6 grid-cols-3 gap-4" x-show="showAnswers">
                        <template x-for="(ans, i) in currentQuestion.answer_paths" :key="i">
                            <button type="button" @click="submitAnswer(currentQuestion.id, i)"
                                :class="['p-3 rounded-lg transition',
                                    selectedAnswer === i ?
                                    'bg-orange-200 ring-2 ring-orange-500' :
                                    'bg-gray-100 hover:bg-orange-100'
                                ]">
                                <img :src="ans" class="mx-auto max-h-32 object-contain">
                            </button>
                        </template>
                    </div>

                    <div class="flex justify-center items-center" x-show="currentQuestion">
                        <div class="text-sm text-gray-600 flex items-center gap-2">
                            <i class="fas fa-clock text-orange-500"></i>
                            Sisa waktu: <span class="font-semibold" x-text="timeLeft + ' detik'"></span>
                        </div>
                    </div>
                </div>
            </template>

            <!-- ======================= -->
            <!-- STAGE 5: SELESAI -->
            <!-- ======================= -->
            <div x-show="stage === Number('{{ count($examples) + 2 }}')" class="text-center mt-10">
                <h2 class="text-2xl font-bold text-orange-600 mb-6">Kuis selesai 🎉</h2>
                <p class="text-gray-600">Akurasi anda.</p>
                <p class="my-4 text-green-600 text-4xl"
                    x-text="`${totalCorrect.reduce((acc, i) => acc+=i)} / ${questions.length}`"></p>
                <div class="mt-8">
                    <button @click="window.location.href='{{ route('user.dashboard') }}'"
                        class="bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700 transition">
                        Kembali ke Dashboard
                    </button>
                </div>
            </div>
        </div>

        <!-- ======================= -->
        <!-- MODAL PRIVACY POLICY -->
        <!-- ======================= -->
        <div x-show="showPrivacy" x-transition x-cloak
            class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
            <div @click.outside="showPrivacy = false"
                class="bg-white rounded-xl shadow-2xl w-full max-w-2xl p-6 relative overflow-y-auto max-h-[80vh]">
                <button @click="showPrivacy = false" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
                <h3 class="text-lg font-semibold text-orange-600 mb-4">Kebijakan Privasi Kuis HMT</h3>
                <div class="custom-trix-content prose max-w-none text-gray-700" x-html="privacy"></div>
                <div class="text-right mt-4">
                    <button @click="showPrivacy = false"
                        class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function quiz(questions, hmtDesc, hmtPrivacy) {
            return {
                stage: 0,
                questions,
                description: hmtDesc,
                privacy: hmtPrivacy,
                privacyAgreed: false,
                showPrivacy: false,
                loading: false,

                // Existing logic
                currentIndex: 0,
                currentQuestion: null,
                showAnswers: false,
                soalAndJawaban: !!Number('{{ $settings[\App\Models\Setting::HMT_SOAL_FIRST] }}'),
                selectedAnswer: null,
                timeLeft: Number('{{ $settings[\App\Models\Setting::HMT_DURATION] }}'),
                totalTime: Number('{{ $settings[\App\Models\Setting::HMT_DURATION] }}'),
                timer: null,
                sessionId: null,
                totalCorrect: new Array(questions.length).fill(0),

                nextStage() {
                    this.stage++;
                },

                async startQuiz() {
                    if (this.loading) return;
                    this.loading = true;

                    try {
                        const response = await fetch("{{ route('quiz.hmt.start') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                            },
                        });

                        const data = await response.json();

                        if (response.ok) {
                            this.sessionId = data.session_id;
                            this.stage++;
                            this.loadQuestion(0);
                        } else {
                            Swal.fire('Gagal memulai kuis', data.message || 'Terjadi kesalahan', 'error');
                        }
                    } catch (err) {
                        Swal.fire('Error', 'Gagal membuat sesi kuis', 'error');
                    } finally {
                        this.loading = false;
                    }
                },

                loadQuestion(index) {
                    this.currentIndex = index;
                    this.currentQuestion = this.questions[index];
                    this.timeLeft = this.totalTime;
                    this.showAnswers = this.soalAndJawaban;
                    this.selectedAnswer = null;

                    if (this.timer) clearInterval(this.timer);
                    this.timer = setInterval(() => {
                        this.timeLeft--;
                        if (this.timeLeft === Math.floor(this.totalTime / 2) && !this.soalAndJawaban) {
                            this.showAnswers = true;
                        }
                        if (this.timeLeft <= 0) {
                            this.nextQuestion();
                        }
                    }, 1000);
                },

                async submitAnswer(questionId, answerIndex) {
                    if (!this.sessionId) return Swal.fire('Error', 'Session belum dimulai.', 'error');
                    this.selectedAnswer = answerIndex;

                    try {
                        const result = await fetch("{{ route('quiz.hmt.answer') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                session_id: this.sessionId,
                                question_id: questionId,
                                answer_index: answerIndex,
                            })
                        });
                        const res = await result.json();
                        this.totalCorrect[this.currentIndex] = res.is_correct ? 1 : 0;
                    } catch (err) {
                        console.error(err);
                    }
                },

                nextQuestion() {
                    clearInterval(this.timer);
                    if (this.currentIndex + 1 < this.questions.length) {
                        this.loadQuestion(this.currentIndex + 1);
                    } else {
                        this.finishQuiz();
                    }
                },

                async finishQuiz() {
                    clearInterval(this.timer);
                    try {
                        await fetch("{{ route('quiz.hmt.finish') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                session_id: this.sessionId
                            })
                        });
                    } catch (err) {
                        console.error(err);
                    }
                    this.currentQuestion = null;
                    this.stage++;
                },
            }
        }
    </script>
@endsection
