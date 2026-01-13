<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hmt_histories', function (Blueprint $table) {
            $table->unsignedTinyInteger('answer_index')->nullable()->change();
            $table->timestamp('answered_at', 6)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hmt_histories', function (Blueprint $table) {
            $table->unsignedTinyInteger('answer_index')->change();
            $table->timestamp('answered_at', 6)->useCurrent()->change();
        });
    }
};
