<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop dulu supaya clean (hati-hati di production)
        Schema::dropIfExists('hmt_histories');

        Schema::create('hmt_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('hmt_sessions')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('hmt_questions')->cascadeOnDelete();
            $table->unsignedTinyInteger('answer_index');
            $table->timestamp('answered_at', 6)->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hmt_histories');
    }
};
