<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLaboranRequest;
use App\Http\Requests\UpdateLaboranRequest;
use App\Models\Laboran;
use Illuminate\Http\JsonResponse;

class LaboranController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['data' => Laboran::all()]);
    }

    public function store(StoreLaboranRequest $request): JsonResponse
    {
        $laboran = Laboran::create($request->validated());

        return response()->json(['data' => $laboran, 'message' => 'Laboran berhasil ditambahkan.'], 201);
    }

    public function show(Laboran $laboran): JsonResponse
    {
        return response()->json(['data' => $laboran]);
    }

    public function update(UpdateLaboranRequest $request, Laboran $laboran): JsonResponse
    {
        if ($laboran->schedules()->exists()) {
            return response()->json(['message' => 'Data tidak dapat diubah karena masih digunakan dalam jadwal.'], 409);
        }

        $laboran->update($request->validated());

        return response()->json(['data' => $laboran, 'message' => 'Laboran berhasil diperbarui.']);
    }

    public function destroy(Laboran $laboran): JsonResponse
    {
        if ($laboran->schedules()->exists()) {
            return response()->json(['message' => 'Data tidak dapat dihapus karena masih digunakan dalam jadwal.'], 409);
        }

        $laboran->delete();

        return response()->json(['message' => 'Laboran berhasil dihapus.'], 200);
    }
}
