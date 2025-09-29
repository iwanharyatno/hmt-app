<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hmt_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question_path'); // misal path file gambar/teks pertanyaan
            $table->json('answer_paths');   // array path jawaban (gambar/teks)
            $table->unsignedTinyInteger('correct_index'); // index jawaban benar
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hmt_questions');
    }
};
