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
        Schema::table('hmt_questions', function (Blueprint $table) {
            $table->boolean('is_example')->default(false);
            $table->text('solution_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hmt_questions', function (Blueprint $table) {
            $table->dropColumn('is_example');
            $table->dropColumn('solution_description');
        });
    }
};
