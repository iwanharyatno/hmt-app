<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('learning_style_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('result'); // hasil utama, misalnya 'Visual'
            $table->json('details')->nullable(); // opsional, simpan breakdown skor
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_style_results');
    }
};
