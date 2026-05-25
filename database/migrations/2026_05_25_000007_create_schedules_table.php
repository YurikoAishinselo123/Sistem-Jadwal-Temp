<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periodes')->cascadeOnDelete();
            $table->enum('schedule_type', ['semester', 'pengganti', 'ujian']);
            $table->foreignId('prodi_id')->constrained('prodis')->cascadeOnDelete();
            $table->foreignId('makul_id')->constrained('makuls')->cascadeOnDelete();
            $table->char('class', 1); // Kelas A-Z
            $table->string('day');    // Hari
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['offline', 'online']);
            $table->foreignId('theory_room_id')->nullable()->constrained('ruangans')->nullOnDelete();
            $table->foreignId('practice_room_id')->nullable()->constrained('ruangans')->nullOnDelete();
            $table->timestamps();

            // Performance indexes
            $table->index(['day', 'start_time', 'end_time'], 'idx_schedules_overlap');
            $table->index(['day', 'theory_room_id'], 'idx_schedules_theory_room_day');
            $table->index(['day', 'practice_room_id'], 'idx_schedules_practice_room_day');
            $table->index(['day', 'makul_id', 'class'], 'idx_schedules_class_day');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
