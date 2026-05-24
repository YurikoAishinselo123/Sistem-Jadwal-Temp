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
            $table->string('academic_year'); // Periode Tahun Ajaran
            $table->enum('schedule_type', ['semester', 'pengganti', 'ujian']);
            $table->foreignId('study_program_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->char('class', 1); // Kelas A-Z
            $table->string('day'); // Hari
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['offline', 'online']);
            $table->foreignId('theory_room_id')->nullable()->constrained('rooms')->nullOnDelete();
            $table->foreignId('practice_room_id')->nullable()->constrained('rooms')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
