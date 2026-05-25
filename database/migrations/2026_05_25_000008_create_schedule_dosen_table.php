<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_dosen', function (Blueprint $table) {
            $table->foreignId('schedule_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dosen_id')->constrained('dosens')->cascadeOnDelete();
            $table->primary(['schedule_id', 'dosen_id']);
            $table->index(['dosen_id', 'schedule_id'], 'idx_sd_dosen_schedule');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_dosen');
    }
};
