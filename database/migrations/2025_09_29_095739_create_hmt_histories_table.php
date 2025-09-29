<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hmt_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('hmt_questions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->unsignedTinyInteger('answer_index'); 
            $table->timestamp('answered_at')->useCurrent(); // ganti timestamp manual
            $table->unsignedInteger('attempts')->default(1); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hmt_histories');
    }
};
