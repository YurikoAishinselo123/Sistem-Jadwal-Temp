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

        $errors = array_merge($errors, $this->checkLecturerConflicts($data, $excludeId));
        $errors = array_merge($errors, $this->checkAssistantConflicts($data, $excludeId));
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
    private function overlapQuery(string $day, string $start, string $end, ?int $excludeId): Builder
    {
        return Schedule::where('day', $day)
            ->where('start_time', '<', $end)    // Overlap condition part 1
            ->where('end_time',   '>', $start)  // Overlap condition part 2
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId));
    }

    // ────────────────────────────────────────────────
    // CHECK 1: Dosen (Lecturer) Conflict
    // ────────────────────────────────────────────────

    /**
     * Checks if any of the provided lecturers already have a class
     * at the given day + overlapping time slot.
     *
     * Strategy: whereHas uses an EXISTS subquery — no JOIN, no duplication.
     * The EXISTS subquery hits idx_sl_lecturer_schedule on the pivot table.
     *
     * SQL equivalent:
     *   SELECT 1 FROM schedules s
     *   WHERE s.day = :day
     *     AND s.start_time < :end AND s.end_time > :start
     *     AND s.id != :excludeId
     *     AND EXISTS (
     *       SELECT 1 FROM schedule_lecturer sl
     *       WHERE sl.schedule_id = s.id
     *         AND sl.lecturer_id IN (:lecturerIds)
     *     )
     */
    private function checkLecturerConflicts(array $data, ?int $excludeId): array
    {
        $conflicts = $this->overlapQuery($data['day'], $data['start_time'], $data['end_time'], $excludeId)
            ->whereHas('lecturers', fn($q) => $q->whereIn('lecturer_id', $data['lecturers']))
            ->with('lecturers:id,name') // fetch names for a clear error message
            ->get(['id', 'day', 'start_time', 'end_time']);

        if ($conflicts->isEmpty()) {
            return [];
        }

        // Identify exactly which lecturers are conflicting
        $conflictingLecturerIds = $conflicts->flatMap(fn($s) => $s->lecturers->pluck('id'))->unique();
        $conflictingNames = collect($data['lecturers'])
            ->filter(fn($id) => $conflictingLecturerIds->contains($id));

        $first        = $conflicts->first();
        $lecturerList = $conflicts->flatMap(fn($s) => $s->lecturers->pluck('name'))->unique()->implode(', ');

        return [
            'lecturers' => [
                "Konflik dosen: {$lecturerList} sudah terjadwal pada hari {$first->day} "
                . "pukul {$this->fmt($first->start_time)}–{$this->fmt($first->end_time)}.",
            ],
        ];
    }

    // ────────────────────────────────────────────────
    // CHECK 2: Laboran (Assistant) Conflict
    // ────────────────────────────────────────────────

    /**
     * Same strategy as lecturer conflict, but for the schedule_assistant pivot.
     */
    private function checkAssistantConflicts(array $data, ?int $excludeId): array
    {
        $conflicts = $this->overlapQuery($data['day'], $data['start_time'], $data['end_time'], $excludeId)
            ->whereHas('assistants', fn($q) => $q->whereIn('assistant_id', $data['assistants']))
            ->with('assistants:id,name')
            ->get(['id', 'day', 'start_time', 'end_time']);

        if ($conflicts->isEmpty()) {
            return [];
        }

        $first         = $conflicts->first();
        $assistantList = $conflicts->flatMap(fn($s) => $s->assistants->pluck('name'))->unique()->implode(', ');

        return [
            'assistants' => [
                "Konflik laboran: {$assistantList} sudah terjadwal pada hari {$first->day} "
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
     *
     * Strategy: Uses a single WHERE clause with OR to check both room columns
     * in one query, leveraging the two separate indexes:
     *   idx_schedules_theory_room_day  (day, theory_room_id)
     *   idx_schedules_practice_room_day (day, practice_room_id)
     *
     * Only runs if at least one room is provided (null rooms are skipped for
     * Online schedules where rooms are optional).
     */
    private function checkRoomConflicts(array $data, ?int $excludeId): array
    {
        $theoryRoomId   = $data['theory_room_id']   ?? null;
        $practiceRoomId = $data['practice_room_id'] ?? null;
        $roomIds        = array_filter([$theoryRoomId, $practiceRoomId]);

        if (empty($roomIds)) {
            return []; // Online schedule — no rooms to check
        }

        $conflict = $this->overlapQuery($data['day'], $data['start_time'], $data['end_time'], $excludeId)
            ->where(function ($q) use ($roomIds) {
                $q->whereIn('theory_room_id',   $roomIds)
                  ->orWhereIn('practice_room_id', $roomIds);
            })
            ->with(['theoryRoom:id,name', 'practiceRoom:id,name'])
            ->first(['id', 'day', 'start_time', 'end_time', 'theory_room_id', 'practice_room_id']);

        if (!$conflict) {
            return [];
        }

        $roomName = $conflict->theoryRoom?->name ?? $conflict->practiceRoom?->name ?? 'Ruangan';

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
     * Checks if the same class (e.g. "Kelas A" of course X) already has a
     * schedule that overlaps with the requested time.
     *
     * Strategy: Narrow query with idx_schedules_class_day (day, course_id, class)
     * then apply the time overlap filter on the pre-narrowed result set.
     */
    private function checkClassConflicts(array $data, ?int $excludeId): array
    {
        $conflict = $this->overlapQuery($data['day'], $data['start_time'], $data['end_time'], $excludeId)
            ->where('course_id', $data['course_id'])
            ->where('class',     $data['class'])
            ->first(['id', 'day', 'start_time', 'end_time', 'class']);

        if (!$conflict) {
            return [];
        }

        return [
            'class' => [
                "Konflik kelas: Kelas {$conflict->class} untuk mata kuliah ini sudah dijadwalkan "
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
