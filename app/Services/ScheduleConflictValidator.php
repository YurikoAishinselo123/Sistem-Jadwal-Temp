<?php

namespace App\Services;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;

/**
 * ScheduleConflictValidator
 *
 * Implements Allen's Interval Algebra overlap theorem:
 *
 *   Two intervals [A_start, A_end) and [B_start, B_end) OVERLAP when:
 *       A_start < B_end  AND  A_end > B_start
 *
 *   They do NOT overlap (adjacent) when:
 *       A_end = B_start  (e.g. 08:00–10:00 and 10:00–12:00 are safe)
 *
 * Architecture:
 *   - All four conflict checks share one private base query builder.
 *   - Each check runs a single indexed query using EXISTS (most efficient).
 *   - Errors are collected and thrown together so the API returns all
 *     conflicts in one response rather than one at a time.
 *   - An optional $excludeId param allows updates to skip self-conflict.
 */
class ScheduleConflictValidator
{
    /**
     * Run all four conflict checks and throw a ValidationException
     * with collected error messages if any conflicts are found.
     *
     * @param  array    $data           Validated form data
     * @param  int|null $excludeId      Schedule ID to exclude (for updates)
     * @throws ValidationException
     */
    public function validateConflicts(array $data, ?int $excludeId = null): void
    {
        $errors = [];

        $errors = array_merge($errors, $this->checkDosenConflicts($data, $excludeId));
        $errors = array_merge($errors, $this->checkLaboranConflicts($data, $excludeId));
        $errors = array_merge($errors, $this->checkRoomConflicts($data, $excludeId));
        $errors = array_merge($errors, $this->checkClassConflicts($data, $excludeId));

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }

    // ────────────────────────────────────────────────
    // PRIVATE: Base Overlap Query Builder
    // ────────────────────────────────────────────────

    /**
     * Returns a base Eloquent Builder pre-scoped to:
     *   - The same day
     *   - Overlapping time window  (start < :end AND end > :start)
     *   - Excluding the current schedule (for update operations)
     *
     * Uses the composite index: idx_schedules_overlap (day, start_time, end_time)
     */
    private function overlapQuery(int $periodeId, string $day, string $start, string $end, ?int $excludeId): Builder
    {
        return Schedule::where('periode_id', $periodeId)
            ->where('day', $day)
            ->where('start_time', '<', $end)    // Overlap condition part 1
            ->where('end_time',   '>', $start)  // Overlap condition part 2
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId));
    }

    // ────────────────────────────────────────────────
    // CHECK 1: Dosen Conflict
    // ────────────────────────────────────────────────

    /**
     * Checks if any of the provided dosens already have a class
     * at the given day + overlapping time slot.
     *
     * Strategy: whereHas uses an EXISTS subquery — no JOIN, no duplication.
     * The EXISTS subquery hits idx_sd_dosen_schedule on the pivot table.
     */
    private function checkDosenConflicts(array $data, ?int $excludeId): array
    {
        $conflicts = $this->overlapQuery($data['periode_id'], $data['day'], $data['start_time'], $data['end_time'], $excludeId)
            ->whereHas('dosens', fn($q) => $q->whereIn('dosen_id', $data['dosens']))
            ->with('dosens:id,nama_dosen')
            ->get(['id', 'day', 'start_time', 'end_time']);

        if ($conflicts->isEmpty()) {
            return [];
        }

        $first      = $conflicts->first();
        $dosenList  = $conflicts->flatMap(fn($s) => $s->dosens->pluck('nama_dosen'))->unique()->implode(', ');

        return [
            'dosens' => [
                "Konflik dosen: {$dosenList} sudah terjadwal pada hari {$first->day} "
                . "pukul {$this->fmt($first->start_time)}–{$this->fmt($first->end_time)}.",
            ],
        ];
    }

    // ────────────────────────────────────────────────
    // CHECK 2: Laboran Conflict
    // ────────────────────────────────────────────────

    /**
     * Same strategy as dosen conflict, but for the schedule_laboran pivot.
     */
    private function checkLaboranConflicts(array $data, ?int $excludeId): array
    {
        $conflicts = $this->overlapQuery($data['periode_id'], $data['day'], $data['start_time'], $data['end_time'], $excludeId)
            ->whereHas('laborans', fn($q) => $q->whereIn('laboran_id', $data['laborans']))
            ->with('laborans:id,nama_laboran')
            ->get(['id', 'day', 'start_time', 'end_time']);

        if ($conflicts->isEmpty()) {
            return [];
        }

        $first        = $conflicts->first();
        $laboranList  = $conflicts->flatMap(fn($s) => $s->laborans->pluck('nama_laboran'))->unique()->implode(', ');

        return [
            'laborans' => [
                "Konflik laboran: {$laboranList} sudah terjadwal pada hari {$first->day} "
                . "pukul {$this->fmt($first->start_time)}–{$this->fmt($first->end_time)}.",
            ],
        ];
    }

    // ────────────────────────────────────────────────
    // CHECK 3: Room Conflict
    // ────────────────────────────────────────────────

    /**
     * Checks if the theory or practice room is already booked during the
     * overlapping time slot.
     */
    private function checkRoomConflicts(array $data, ?int $excludeId): array
    {
        $theoryRoomId   = $data['theory_room_id']   ?? null;
        $practiceRoomId = $data['practice_room_id'] ?? null;
        $roomIds        = array_filter([$theoryRoomId, $practiceRoomId]);

        if (empty($roomIds)) {
            return []; // Online schedule — no rooms to check
        }

        $conflict = $this->overlapQuery($data['periode_id'], $data['day'], $data['start_time'], $data['end_time'], $excludeId)
            ->where(function ($q) use ($roomIds) {
                $q->whereIn('theory_room_id',   $roomIds)
                  ->orWhereIn('practice_room_id', $roomIds);
            })
            ->with(['theoryRoom:id,nama_ruangan', 'practiceRoom:id,nama_ruangan'])
            ->first(['id', 'day', 'start_time', 'end_time', 'theory_room_id', 'practice_room_id']);

        if (!$conflict) {
            return [];
        }

        $roomName = $conflict->theoryRoom?->nama_ruangan ?? $conflict->practiceRoom?->nama_ruangan ?? 'Ruangan';

        return [
            'theory_room_id' => [
                "Konflik ruangan: {$roomName} sudah digunakan pada hari {$conflict->day} "
                . "pukul {$this->fmt($conflict->start_time)}–{$this->fmt($conflict->end_time)}.",
            ],
        ];
    }

    // ────────────────────────────────────────────────
    // CHECK 4: Kelas Conflict
    // ────────────────────────────────────────────────

    /**
     * Checks if the same class of makul X already has a schedule that
     * overlaps with the requested time.
     *
     * Strategy: Narrow query with idx_schedules_class_day (day, makul_id, class)
     */
    private function checkClassConflicts(array $data, ?int $excludeId): array
    {
        $conflict = $this->overlapQuery($data['periode_id'], $data['day'], $data['start_time'], $data['end_time'], $excludeId)
            ->where('makul_id', $data['makul_id'])
            ->where('class',    $data['class'])
            ->first(['id', 'day', 'start_time', 'end_time', 'class']);

        if (!$conflict) {
            return [];
        }

        return [
            'class' => [
                "Konflik kelas: Kelas {$conflict->class} untuk makul ini sudah dijadwalkan "
                . "pada hari {$conflict->day} pukul "
                . "{$this->fmt($conflict->start_time)}–{$this->fmt($conflict->end_time)}.",
            ],
        ];
    }

    // ────────────────────────────────────────────────
    // HELPER
    // ────────────────────────────────────────────────

    /** Format a HH:MM:SS database time value to HH:MM for display */
    private function fmt(string $time): string
    {
        return substr($time, 0, 5);
    }
}
