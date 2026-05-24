<?php

namespace App\Services;

use App\Models\Schedule;
use Illuminate\Support\Facades\DB;

/**
 * ScheduleService
 *
 * Wraps all schedule mutations inside a database transaction with
 * pessimistic locking (SELECT ... FOR UPDATE) to prevent race conditions
 * when two requests try to book the same slot concurrently.
 *
 * Race Condition Scenario:
 *   Request A checks conflicts → no conflict found
 *   Request B checks conflicts → no conflict found  (before A commits)
 *   Request A inserts → success
 *   Request B inserts → duplicate conflict exists — BUG!
 *
 * Solution: lockForUpdate() acquires a row-level lock inside the transaction.
 * When Request A locks the relevant rows, Request B's conflict-check query
 * will WAIT until A's transaction commits, then re-evaluate safely.
 */
class ScheduleService
{
    public function __construct(
        private ScheduleConflictValidator $conflictValidator
    ) {}

    public function createSchedule(array $data): Schedule
    {
        return DB::transaction(function () use ($data) {
            // Acquire advisory lock on the day/time band before checking conflicts.
            // Any concurrent transaction trying the same day will queue here.
            $this->acquireScheduleLock($data['day'], $data['start_time'], $data['end_time']);

            $this->conflictValidator->validateConflicts($data);

            $schedule = Schedule::create($this->extractScheduleData($data));
            $schedule->lecturers()->attach($data['lecturers']);
            $schedule->assistants()->attach($data['assistants']);

            return $schedule->load([
                'lecturers', 'assistants', 'course',
                'studyProgram', 'theoryRoom', 'practiceRoom',
            ]);
        });
    }

    public function updateSchedule(Schedule $schedule, array $data): Schedule
    {
        return DB::transaction(function () use ($schedule, $data) {
            // Lock the existing row to prevent concurrent updates to the same schedule
            Schedule::lockForUpdate()->find($schedule->id);

            // Lock the time band for the new requested slot
            $this->acquireScheduleLock($data['day'], $data['start_time'], $data['end_time']);

            $this->conflictValidator->validateConflicts($data, $schedule->id);

            $schedule->update($this->extractScheduleData($data));
            $schedule->lecturers()->sync($data['lecturers']);
            $schedule->assistants()->sync($data['assistants']);

            return $schedule->load([
                'lecturers', 'assistants', 'course',
                'studyProgram', 'theoryRoom', 'practiceRoom',
            ]);
        });
    }

    public function deleteSchedule(Schedule $schedule): void
    {
        DB::transaction(function () use ($schedule) {
            // Detach pivot records first, then delete parent
            // (cascadeOnDelete in migration handles this automatically,
            //  but explicit detach is clearer and avoids FK edge cases)
            $schedule->lecturers()->detach();
            $schedule->assistants()->detach();
            $schedule->delete();
        });
    }

    // ────────────────────────────────────────────────────────────
    // RACE CONDITION PREVENTION
    // ────────────────────────────────────────────────────────────

    /**
     * Pessimistic lock: SELECT all schedules on the same day that touch
     * the same time window using FOR UPDATE. This causes concurrent
     * transactions targeting the same slot to WAIT in a queue,
     * guaranteeing only one can proceed at a time.
     *
     * Alternative: DB::statement("SELECT GET_LOCK('schedule_{$day}', 10)")
     * for a named advisory lock if row-level locking is too broad.
     */
    private function acquireScheduleLock(string $day, string $start, string $end): void
    {
        Schedule::lockForUpdate()
            ->where('day', $day)
            ->where('start_time', '<', $end)
            ->where('end_time',   '>', $start)
            ->get(['id']); // Fetch IDs to acquire the locks
    }

    // ────────────────────────────────────────────────────────────
    // HELPERS
    // ────────────────────────────────────────────────────────────

    private function extractScheduleData(array $data): array
    {
        return [
            'academic_year'    => $data['academic_year'],
            'schedule_type'    => $data['schedule_type'],
            'study_program_id' => $data['study_program_id'],
            'course_id'        => $data['course_id'],
            'class'            => $data['class'],
            'day'              => $data['day'],
            'start_time'       => $data['start_time'],
            'end_time'         => $data['end_time'],
            'status'           => $data['status'],
            'theory_room_id'   => $data['theory_room_id']   ?? null,
            'practice_room_id' => $data['practice_room_id'] ?? null,
        ];
    }
}
