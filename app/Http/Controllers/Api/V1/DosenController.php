<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDosenRequest;
use App\Http\Requests\UpdateDosenRequest;
use App\Models\Dosen;
use Illuminate\Http\JsonResponse;

class DosenController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['data' => Dosen::all()]);
    }

    public function store(StoreDosenRequest $request): JsonResponse
    {
        $dosen = Dosen::create($request->validated());

        return response()->json(['data' => $dosen, 'message' => 'Dosen berhasil ditambahkan.'], 201);
    }

    public function show(Dosen $dosen): JsonResponse
    {
        return response()->json(['data' => $dosen]);
    }

    public function update(UpdateDosenRequest $request, Dosen $dosen): JsonResponse
    {
        if ($dosen->schedules()->exists()) {
            return response()->json(['message' => 'Data tidak dapat diubah karena masih digunakan dalam jadwal.'], 409);
        }

        $dosen->update($request->validated());

        return response()->json(['data' => $dosen, 'message' => 'Dosen berhasil diperbarui.']);
    }

    public function destroy(Dosen $dosen): JsonResponse
    {
        if ($dosen->schedules()->exists()) {
            return response()->json(['message' => 'Data tidak dapat dihapus karena masih digunakan dalam jadwal.'], 409);
        }

        $dosen->delete();

        return response()->json(['message' => 'Dosen berhasil dihapus.'], 200);
    }
}
