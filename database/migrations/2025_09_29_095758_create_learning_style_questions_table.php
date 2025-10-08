<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('learning_style_questions', function (Blueprint $table) {
            $table->id();
            $table->text('question')->nullable();
            $table->json('answers')->nullable(); // array berisi 2 pilihan jawaban
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_style_questions');
    }
};
