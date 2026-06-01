<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProdiRequest;
use App\Http\Requests\UpdateProdiRequest;
use App\Models\Prodi;
use Illuminate\Http\JsonResponse;

class ProdiController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['data' => Prodi::all()]);
    }

    public function store(StoreProdiRequest $request): JsonResponse
    {
        $prodi = Prodi::create($request->validated());

        return response()->json(['data' => $prodi, 'message' => 'Prodi berhasil ditambahkan.'], 201);
    }

    public function show(Prodi $prodi): JsonResponse
    {
        return response()->json(['data' => $prodi]);
    }

    public function update(UpdateProdiRequest $request, Prodi $prodi): JsonResponse
    {
        $prodi->update($request->validated());

        return response()->json(['data' => $prodi, 'message' => 'Prodi berhasil diperbarui.']);
    }

    public function destroy(Prodi $prodi): JsonResponse
    {
        if (\App\Models\Schedule::where('prodi_id', $prodi->id)->exists()) {
            return response()->json(['message' => 'Data tidak dapat dihapus karena masih digunakan dalam jadwal.'], 409);
        }

        $prodi->delete();

        return response()->json(['message' => 'Prodi berhasil dihapus.'], 200);
    }
}
