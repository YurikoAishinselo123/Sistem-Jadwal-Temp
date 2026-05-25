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
        $schedules = Schedule::with(['dosens', 'laborans', 'makul', 'prodi', 'theoryRoom', 'practiceRoom', 'periode'])->get();

        return response()->json(['data' => $schedules]);
    }

    public function store(StoreScheduleRequest $request): JsonResponse
    {
        $schedule = $this->scheduleService->createSchedule($request->validated());

        return response()->json(['data' => $schedule, 'message' => 'Jadwal berhasil ditambahkan.'], 201);
    }

    public function show(Schedule $schedule): JsonResponse
    {
        $schedule->load(['dosens', 'laborans', 'makul', 'prodi', 'theoryRoom', 'practiceRoom', 'periode']);

        return response()->json(['data' => $schedule]);
    }

    public function update(UpdateScheduleRequest $request, Schedule $schedule): JsonResponse
    {
        $updatedSchedule = $this->scheduleService->updateSchedule($schedule, $request->validated());

        return response()->json(['data' => $updatedSchedule, 'message' => 'Jadwal berhasil diperbarui.']);
    }

    public function destroy(Schedule $schedule): JsonResponse
    {
        $this->scheduleService->deleteSchedule($schedule);

        return response()->json(['message' => 'Jadwal berhasil dihapus.'], 200);
    }
}
