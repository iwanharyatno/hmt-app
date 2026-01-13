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
        Schema::table('hmt_sessions', function (Blueprint $table) {
            $table->timestamp('started_at', 3)->useCurrent()->change();
            $table->timestamp('finished_at', 3)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hmt_sessions', function (Blueprint $table) {
            $table->timestamp('started_at')->useCurrent()->change();
            $table->timestamp('finished_at')->nullable()->change();
        });
    }
};
