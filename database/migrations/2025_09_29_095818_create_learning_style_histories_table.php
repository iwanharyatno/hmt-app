<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('learning_style_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('learning_style_questions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('answer_index'); // 0 atau 1
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_style_histories');
    }
};