<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePeriodeRequest;
use App\Http\Requests\UpdatePeriodeRequest;
use App\Models\Periode;
use Illuminate\Http\JsonResponse;

class PeriodeController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['data' => Periode::all()]);
    }

    public function store(StorePeriodeRequest $request): JsonResponse
    {
        $periode = Periode::create($request->validated());

        return response()->json(['data' => $periode, 'message' => 'Periode berhasil ditambahkan.'], 201);
    }

    public function show(Periode $periode): JsonResponse
    {
        return response()->json(['data' => $periode]);
    }

    public function update(UpdatePeriodeRequest $request, Periode $periode): JsonResponse
    {
        if ($periode->isLocked()) {
            return response()->json([
                'message' => 'Periode ini sudah ditutup dan tidak dapat diubah.',
            ], 403);
        }

        $periode->update($request->validated());

        return response()->json(['data' => $periode, 'message' => 'Periode berhasil diperbarui.']);
    }

    public function destroy(Periode $periode): JsonResponse
    {
        if ($periode->isLocked()) {
            return response()->json([
                'message' => 'Periode ini sudah ditutup dan tidak dapat dihapus.',
            ], 403);
        }

        $periode->delete();

        return response()->json(['message' => 'Periode berhasil dihapus.'], 200);
    }

    /**
     * Tutup Periode: locks it so no further edits or deletes are allowed.
     */
    public function tutup(Periode $periode): JsonResponse
    {
        if ($periode->isLocked()) {
            return response()->json([
                'message' => 'Periode ini sudah ditutup sebelumnya.',
            ], 422);
        }

        $periode->tutupPeriode();

        return response()->json([
            'data'    => $periode->fresh(),
            'message' => 'Periode berhasil ditutup. Seluruh data dalam periode ini bersifat read-only.',
        ]);
    }
}
