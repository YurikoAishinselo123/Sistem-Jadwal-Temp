<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Laboran;
use App\Models\Makul;
use App\Models\Periode;
use App\Models\Prodi;
use App\Models\Ruangan;
use Illuminate\Http\JsonResponse;

class MasterDataController extends Controller
{
    /**
     * Return all master data in one response.
     * Useful for populating frontend dropdowns.
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'makuls'   => Makul::all(),
            'dosens'   => Dosen::all(),
            'laborans' => Laboran::all(),
            'prodis'   => Prodi::all(),
            'ruangans' => Ruangan::all(),
            'periodes' => Periode::all(),
        ]);
    }
}
