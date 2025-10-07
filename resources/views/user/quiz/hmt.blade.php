@extends('layouts.plain')

@section('title', 'Kuis HMT')

@section('content')
    <div class="bg-white h-full items-center flex absolute top-0 left-0 w-full" x-data="quiz({{ $questions->toJson() }})">
        <div class="max-w-4xl grow mx-auto px-6 lg:px-12">
            <template x-if="showPreface">
                <div class="shadow-xl p-4">
                    <h1 class="text-2xl font-bold text-center mb-3">Kuis HMT</h1>
                    <p class="mb-4">Hagen Matrices Test adalah kuis untuk menguji pola pikir</p>
                    <button @click="startQuiz()"
                        class="inline-block px-3 py-1 bg-orange-500 text-white rounded hover:bg-orange-600 transition">
                        Mulai Test
                    </button>
                </div>
            </template>
            <!-- Pertanyaan -->
            <template x-if="currentQuestion && !showPreface">
                <div class="bg-white rounded-2xl shadow-md p-6 mb-8">
                    <h2 class="text-lg text-center font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-puzzle-piece text-pink-600"></i>
                        Pertanyaan <span x-text="currentIndex + 1"></span> dari <span x-text="questions.length"></span>
                    </h2>

                    <!-- Gambar Soal -->
                    <div class="flex justify-center mb-6">
                        <img :src="currentQuestion.question_path" alt="Soal HMT" class="rounded-lg shadow max-h-80">
                    </div>

                    <!-- Pilihan Jawaban -->
                    <div class="grid grid-cols-6 gap-4" x-show="showAnswers">
                        <template x-for="(ans, i) in currentQuestion.answer_paths" :key="i">
                            <div>
                                <button type="button" @click="submitAnswer(currentQuestion.id, i)"
                                    :class="['p-3 rounded-lg transition',
                                        selectedAnswer === i ?
                                        'bg-pink-200 ring-2 ring-pink-500' :
                                        'bg-gray-100 hover:bg-pink-100'
                                    ]">
                                    <img :src="ans" class="mx-auto max-h-32 object-contain">
                                </button>
                            </div>
                        </template>
                    </div>

                    <!-- Timer -->
                    <div class="flex justify-center items-center" x-show="currentQuestion">
                        <div class="text-sm text-gray-600 flex items-center gap-2">
                            <i class="fas fa-clock text-pink-500"></i>
                            Sisa waktu: <span class="font-semibold" x-text="timeLeft + ' detik'"></span>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Selesai -->
            <div x-show="!currentQuestion && !showPreface" class="text-center mt-10">
                <h2 class="text-2xl font-bold text-green-600">Kuis selesai 🎉</h2>
                <p class="text-gray-600">Terima kasih sudah mengerjakan.</p>
            </div>

        </div>
    </div>

    <script>
        function quiz(questions) {
            return {
                questions: questions,
                currentIndex: 0,
                currentQuestion: null,
                showPreface: true,
                timeLeft: 30,
                timer: null,
                showAnswers: false,
                selectedAnswer: null,

                startQuiz() {
                    if (this.questions.length > 0) {
                        this.loadQuestion(0);
                    }
                    this.showPreface = false;
                },

                loadQuestion(index) {
                    this.currentIndex = index;
                    this.currentQuestion = this.questions[index];
                    this.timeLeft = 30;
                    this.showAnswers = false;
                    this.selectedAnswer = null;

                    if (this.timer) clearInterval(this.timer);
                    this.timer = setInterval(() => {
                        this.timeLeft--;
                        if (this.timeLeft === 15) {
                            this.showAnswers = true;
                        }
                        if (this.timeLeft <= 0) {
                            this.nextQuestion();
                        }
                    }, 1000);
                },

                nextQuestion() {
                    clearInterval(this.timer);
                    if (this.currentIndex + 1 < this.questions.length) {
                        this.loadQuestion(this.currentIndex + 1);
                    } else {
                        this.currentQuestion = null;
                    }
                },

                async submitAnswer(questionId, answerIndex) {
                    this.selectedAnswer = answerIndex;
                    try {
                        await fetch("{{ route('quiz.hmt.answer') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                question_id: questionId,
                                answer_index: answerIndex,
                            })
                        });
                    } catch (err) {
                        console.error(err);
                    }
                }
            }
        }
    </script>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
