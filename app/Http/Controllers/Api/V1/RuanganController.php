<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRuanganRequest;
use App\Http\Requests\UpdateRuanganRequest;
use App\Models\Ruangan;
use Illuminate\Http\JsonResponse;

class RuanganController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['data' => Ruangan::all()]);
    }

    public function store(StoreRuanganRequest $request): JsonResponse
    {
        $ruangan = Ruangan::create($request->validated());

        return response()->json(['data' => $ruangan, 'message' => 'Ruangan berhasil ditambahkan.'], 201);
    }

    public function show(Ruangan $ruangan): JsonResponse
    {
        return response()->json(['data' => $ruangan]);
    }

    public function update(UpdateRuanganRequest $request, Ruangan $ruangan): JsonResponse
    {
        if (\App\Models\Schedule::where('theory_room_id', $ruangan->id)->orWhere('practice_room_id', $ruangan->id)->exists()) {
            return response()->json(['message' => 'Data tidak dapat diubah karena masih digunakan dalam jadwal.'], 409);
        }

        $ruangan->update($request->validated());

        return response()->json(['data' => $ruangan, 'message' => 'Ruangan berhasil diperbarui.']);
    }

    public function destroy(Ruangan $ruangan): JsonResponse
    {
        if (\App\Models\Schedule::where('theory_room_id', $ruangan->id)->orWhere('practice_room_id', $ruangan->id)->exists()) {
            return response()->json(['message' => 'Data tidak dapat dihapus karena masih digunakan dalam jadwal.'], 409);
        }

        $ruangan->delete();

        return response()->json(['message' => 'Ruangan berhasil dihapus.'], 200);
    }
}
