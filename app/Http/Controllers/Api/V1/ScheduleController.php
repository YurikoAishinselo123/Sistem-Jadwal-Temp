<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\Schedule;
use App\Services\ScheduleService;
use Illuminate\Http\JsonResponse;

class ScheduleController extends Controller
{
    public function __construct(
        private ScheduleService $scheduleService
    ) {}

    public function index(): JsonResponse
    {
        $schedules = Schedule::with(['lecturers', 'assistants', 'course', 'theoryRoom', 'practiceRoom'])->get();
        return response()->json(['data' => $schedules]);
    }

    public function store(StoreScheduleRequest $request): JsonResponse
    {
        $schedule = $this->scheduleService->createSchedule($request->validated());
        return response()->json(['data' => $schedule, 'message' => 'Schedule created successfully.'], 201);
    }

    public function show(Schedule $schedule): JsonResponse
    {
        $schedule->load(['lecturers', 'assistants', 'course', 'theoryRoom', 'practiceRoom']);
        return response()->json(['data' => $schedule]);
    }

    public function update(UpdateScheduleRequest $request, Schedule $schedule): JsonResponse
    {
        $updatedSchedule = $this->scheduleService->updateSchedule($schedule, $request->validated());
        return response()->json(['data' => $updatedSchedule, 'message' => 'Schedule updated successfully.']);
    }

    public function destroy(Schedule $schedule): JsonResponse
    {
        $this->scheduleService->deleteSchedule($schedule);
        return response()->json(['message' => 'Schedule deleted successfully.'], 204);
    }
}
