<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BebanKerjaDosenRequest;
use App\Http\Requests\BebanKerjaLaboranRequest;
use App\Http\Requests\BebanKerjaRuanganRequest;
use App\Models\Schedule;
use Illuminate\Http\JsonResponse;

class BebanKerjaController extends Controller
{
    /**
     * Get workload for a specific Dosen and Periode.
     */
    public function dosen(BebanKerjaDosenRequest $request): JsonResponse
    {
        $dosenId = $request->input('dosen_id');
        $periodeId = $request->input('periode_id');

        $schedules = Schedule::with(['makul', 'theoryRoom', 'practiceRoom'])
            ->where('periode_id', $periodeId)
            ->whereHas('dosens', function ($query) use ($dosenId) {
                $query->where('dosen_id', $dosenId);
            })
            ->get();

        $totalSesiMengajar = 0;
        $totalSksTeori = 0;
        $totalSksPraktik = 0;

        $mappedSchedules = $schedules->map(function ($schedule) use (&$totalSesiMengajar, &$totalSksTeori, &$totalSksPraktik) {
            $makul = $schedule->makul;
            
            // Assume 1 schedule entry = 1 sesi mapping for simplicity of "total sesi" overall.
            // If the requirement meant "Total Sesi" per makul, we extract it.
            // Based on requirements: "Total Sesi, Total SKS" per schedule item.
            // A schedule typically represents 1 block of teaching.
            // We'll aggregate based on the Makul's totals.
            
            $sesiMakul = $makul->jumlah_sesi_teori + $makul->jumlah_sesi_praktek;
            $sksMakul = $makul->jumlah_sks_teori + $makul->jumlah_sks_praktek;

            $totalSesiMengajar += $sesiMakul;
            $totalSksTeori += $makul->jumlah_sks_teori;
            $totalSksPraktik += $makul->jumlah_sks_praktek;

            // Determine room name and type based on status and rooms
            $ruangan = null;
            $jenisRuangan = null;
            
            if ($schedule->status === 'online') {
                $ruangan = 'Online';
                $jenisRuangan = 'Online';
            } else {
                if ($schedule->theory_room_id) {
                    $ruangan = $schedule->theoryRoom->nama_ruangan;
                    $jenisRuangan = 'Teori';
                } elseif ($schedule->practice_room_id) {
                    $ruangan = $schedule->practiceRoom->nama_ruangan;
                    $jenisRuangan = 'Praktik';
                }
            }

            return [
                'id' => $schedule->id,
                'mata_kuliah' => $makul->nama_makul,
                'kelas' => $schedule->class,
                'hari' => $schedule->day,
                'jam_mulai' => substr($schedule->start_time, 0, 5),
                'jam_selesai' => substr($schedule->end_time, 0, 5),
                'ruangan' => $ruangan,
                'jenis_ruangan' => $jenisRuangan,
                'total_sesi' => $sesiMakul,
                'total_sks' => $sksMakul,
            ];
        });

        return response()->json([
            'data' => [
                'schedules' => $mappedSchedules,
                'summary' => [
                    'total_mengajar_sesi' => $totalSesiMengajar,
                    'total_sks_teori' => $totalSksTeori,
                    'total_sks_praktik' => $totalSksPraktik,
                    'total_keseluruhan_beban' => $totalSksTeori + $totalSksPraktik,
                ]
            ]
        ]);
    }

    /**
     * Get workload for a specific Ruangan and Periode.
     */
    public function ruangan(BebanKerjaRuanganRequest $request): JsonResponse
    {
        $ruanganId = $request->input('ruangan_id');
        $periodeId = $request->input('periode_id');

        $schedules = Schedule::with(['makul', 'dosens'])
            ->where('periode_id', $periodeId)
            ->where(function ($query) use ($ruanganId) {
                $query->where('theory_room_id', $ruanganId)
                      ->orWhere('practice_room_id', $ruanganId);
            })
            ->get();

        $totalPenggunaan = $schedules->count();
        $totalSesiPenggunaan = 0;

        $mappedSchedules = $schedules->map(function ($schedule) use (&$totalSesiPenggunaan, $ruanganId) {
            $makul = $schedule->makul;
            $dosens = $schedule->dosens->pluck('nama_dosen')->implode(', ');
            
            // Determine the role of the room in this schedule
            $jenisRuangan = 'Teori';
            $sesi = $makul->jumlah_sesi_teori;

            if ($schedule->practice_room_id == $ruanganId) {
                $jenisRuangan = 'Praktik';
                $sesi = $makul->jumlah_sesi_praktek;
            }

            $totalSesiPenggunaan += $sesi;

            return [
                'id' => $schedule->id,
                'mata_kuliah' => $makul->nama_makul,
                'dosen' => $dosens,
                'hari' => $schedule->day,
                'jam_mulai' => substr($schedule->start_time, 0, 5),
                'jam_selesai' => substr($schedule->end_time, 0, 5),
                'kelas' => $schedule->class,
                'jenis_ruangan' => $jenisRuangan,
            ];
        });

        return response()->json([
            'data' => [
                'schedules' => $mappedSchedules,
                'summary' => [
                    'total_penggunaan_ruangan' => $totalPenggunaan,
                    'total_sesi_penggunaan_ruangan' => $totalSesiPenggunaan,
                ]
            ]
        ]);
    }

    /**
     * Get workload for a specific Laboran and Periode.
     */
    public function laboran(BebanKerjaLaboranRequest $request): JsonResponse
    {
        $laboranId = $request->input('laboran_id');
        $periodeId = $request->input('periode_id');

        $schedules = Schedule::with(['makul', 'prodi', 'theoryRoom', 'practiceRoom'])
            ->where('periode_id', $periodeId)
            ->whereHas('laborans', function ($query) use ($laboranId) {
                $query->where('laboran_id', $laboranId);
            })
            ->get();

        $totalJadwal = $schedules->count();
        $totalSesiLaboran = 0;

        $mappedSchedules = $schedules->map(function ($schedule) use (&$totalSesiLaboran) {
            $makul = $schedule->makul;

            $sesiMakul = $makul->jumlah_sesi_teori + $makul->jumlah_sesi_praktek;
            $totalSesiLaboran += $sesiMakul;

            $ruangan = null;
            $jenisRuangan = null;

            if ($schedule->status === 'online') {
                $ruangan = 'Online';
                $jenisRuangan = 'Online';
            } else {
                if ($schedule->practice_room_id) {
                    $ruangan = $schedule->practiceRoom?->nama_ruangan;
                    $jenisRuangan = 'Praktik';
                } elseif ($schedule->theory_room_id) {
                    $ruangan = $schedule->theoryRoom?->nama_ruangan;
                    $jenisRuangan = 'Teori';
                }
            }

            return [
                'id' => $schedule->id,
                'mata_kuliah' => $makul->nama_makul,
                'program_studi' => $schedule->prodi?->nama_prodi,
                'kelas' => $schedule->class,
                'hari' => $schedule->day,
                'jam_mulai' => substr($schedule->start_time, 0, 5),
                'jam_selesai' => substr($schedule->end_time, 0, 5),
                'ruangan' => $ruangan,
                'jenis_ruangan' => $jenisRuangan,
                'total_sesi' => $sesiMakul,
            ];
        });

        return response()->json([
            'data' => [
                'schedules' => $mappedSchedules,
                'summary' => [
                    'total_jadwal_laboran' => $totalJadwal,
                    'total_sesi_laboran' => $totalSesiLaboran,
                ]
            ]
        ]);
    }
}
