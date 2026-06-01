<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMakulRequest;
use App\Http\Requests\UpdateMakulRequest;
use App\Models\Makul;
use Illuminate\Http\JsonResponse;

class MakulController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['data' => Makul::all()]);
    }

    public function store(StoreMakulRequest $request): JsonResponse
    {
        $makul = Makul::create($request->validated());

        return response()->json(['data' => $makul, 'message' => 'Makul berhasil ditambahkan.'], 201);
    }

    public function show(Makul $makul): JsonResponse
    {
        return response()->json(['data' => $makul]);
    }

    public function update(UpdateMakulRequest $request, Makul $makul): JsonResponse
    {
        if (\App\Models\Schedule::where('makul_id', $makul->id)->exists()) {
            return response()->json(['message' => 'Data tidak dapat diubah karena masih digunakan dalam jadwal.'], 409);
        }

        $makul->update($request->validated());

        return response()->json(['data' => $makul, 'message' => 'Makul berhasil diperbarui.']);
    }

    public function destroy(Makul $makul): JsonResponse
    {
        if (\App\Models\Schedule::where('makul_id', $makul->id)->exists()) {
            return response()->json(['message' => 'Data tidak dapat dihapus karena masih digunakan dalam jadwal.'], 409);
        }

        $makul->delete();

        return response()->json(['message' => 'Makul berhasil dihapus.'], 200);
    }
}
