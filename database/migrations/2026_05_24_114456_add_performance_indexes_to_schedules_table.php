<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Performance Index Strategy for Schedule Conflict Detection
 *
 * Conflict queries always filter by:
 *   1. `day`        — narrow the dataset to one day
 *   2. `start_time` — used in overlap range check: start_time < :end
 *   3. `end_time`   — used in overlap range check: end_time > :start
 *
 * Composite index order: (day, start_time, end_time)
 * MySQL uses leftmost-prefix matching, so this covers:
 *   - WHERE day = ?
 *   - WHERE day = ? AND start_time < ?
 *   - WHERE day = ? AND start_time < ? AND end_time > ?
 *
 * Pivot table indexes optimize JOIN / whereHas lookups.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Core overlap detection index — most critical
            $table->index(['day', 'start_time', 'end_time'], 'idx_schedules_overlap');

            // Room conflict lookups
            $table->index(['day', 'theory_room_id'],   'idx_schedules_theory_room_day');
            $table->index(['day', 'practice_room_id'], 'idx_schedules_practice_room_day');

            // Class conflict lookup
            $table->index(['day', 'course_id', 'class'], 'idx_schedules_class_day');
        });

        Schema::table('schedule_lecturer', function (Blueprint $table) {
            // Optimises: whereHas('lecturers', fn($q) => $q->whereIn('lecturer_id', [...]))
            $table->index(['lecturer_id', 'schedule_id'], 'idx_sl_lecturer_schedule');
        });

        Schema::table('schedule_assistant', function (Blueprint $table) {
            // Optimises: whereHas('assistants', fn($q) => $q->whereIn('assistant_id', [...]))
            $table->index(['assistant_id', 'schedule_id'], 'idx_sa_assistant_schedule');
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropIndex('idx_schedules_overlap');
            $table->dropIndex('idx_schedules_theory_room_day');
            $table->dropIndex('idx_schedules_practice_room_day');
            $table->dropIndex('idx_schedules_class_day');
        });

        Schema::table('schedule_lecturer', function (Blueprint $table) {
            $table->dropIndex('idx_sl_lecturer_schedule');
        });

        Schema::table('schedule_assistant', function (Blueprint $table) {
            $table->dropIndex('idx_sa_assistant_schedule');
        });
    }
};
