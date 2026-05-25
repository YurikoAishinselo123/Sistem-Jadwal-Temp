<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Laboran;
use App\Models\Makul;
use App\Models\Periode;
use App\Models\Prodi;
use App\Models\Ruangan;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Makul ────────────────────────────────────────────
        $makuls = [
            ['kode_makul' => 'MK001', 'nama_makul' => 'Pemrograman Web',          'jumlah_sesi_teori' => 2, 'jumlah_sesi_praktek' => 3],
            ['kode_makul' => 'MK002', 'nama_makul' => 'Basis Data',               'jumlah_sesi_teori' => 2, 'jumlah_sesi_praktek' => 3],
            ['kode_makul' => 'MK003', 'nama_makul' => 'Algoritma dan Struktur Data', 'jumlah_sesi_teori' => 3, 'jumlah_sesi_praktek' => 0],
            ['kode_makul' => 'MK004', 'nama_makul' => 'Jaringan Komputer',        'jumlah_sesi_teori' => 2, 'jumlah_sesi_praktek' => 6],
            ['kode_makul' => 'MK005', 'nama_makul' => 'Sistem Operasi',           'jumlah_sesi_teori' => 2, 'jumlah_sesi_praktek' => 3],
        ];
        foreach ($makuls as $data) {
            Makul::firstOrCreate(['kode_makul' => $data['kode_makul']], $data);
        }

        // ─── Dosen ────────────────────────────────────────────
        $dosens = [
            ['kode_dosen' => 'DSN001', 'nama_dosen' => 'Dr. Ahmad Fauzi, M.Kom'],
            ['kode_dosen' => 'DSN002', 'nama_dosen' => 'Ir. Budi Santoso, M.T.'],
            ['kode_dosen' => 'DSN003', 'nama_dosen' => 'Dra. Cahyani Putri, M.Pd.'],
            ['kode_dosen' => 'DSN004', 'nama_dosen' => 'Dr. Dian Kusuma, S.T., M.T.'],
            ['kode_dosen' => 'DSN005', 'nama_dosen' => 'Eko Prasetyo, S.Kom., M.Cs.'],
        ];
        foreach ($dosens as $data) {
            Dosen::firstOrCreate(['kode_dosen' => $data['kode_dosen']], $data);
        }

        // ─── Laboran ──────────────────────────────────────────
        $laborans = [
            ['kode_laboran' => 'LBR001', 'nama_laboran' => 'Fajar Nugroho'],
            ['kode_laboran' => 'LBR002', 'nama_laboran' => 'Gita Rahayu'],
            ['kode_laboran' => 'LBR003', 'nama_laboran' => 'Hendra Wijaya'],
        ];
        foreach ($laborans as $data) {
            Laboran::firstOrCreate(['kode_laboran' => $data['kode_laboran']], $data);
        }

        // ─── Prodi ────────────────────────────────────────────
        $prodis = [
            ['kode_prodi' => 'TI',  'nama_prodi' => 'Teknik Informatika'],
            ['kode_prodi' => 'SI',  'nama_prodi' => 'Sistem Informasi'],
            ['kode_prodi' => 'KA',  'nama_prodi' => 'Komputerisasi Akuntansi'],
        ];
        foreach ($prodis as $data) {
            Prodi::firstOrCreate(['kode_prodi' => $data['kode_prodi']], $data);
        }

        // ─── Ruangan ──────────────────────────────────────────
        $ruangans = [
            ['kode_ruangan' => 'T101', 'nama_ruangan' => 'Ruang Teori 101', 'jenis_ruangan' => 'teori'],
            ['kode_ruangan' => 'T102', 'nama_ruangan' => 'Ruang Teori 102', 'jenis_ruangan' => 'teori'],
            ['kode_ruangan' => 'T103', 'nama_ruangan' => 'Ruang Teori 103', 'jenis_ruangan' => 'teori'],
            ['kode_ruangan' => 'L201', 'nama_ruangan' => 'Lab Komputer 1',  'jenis_ruangan' => 'praktik'],
            ['kode_ruangan' => 'L202', 'nama_ruangan' => 'Lab Komputer 2',  'jenis_ruangan' => 'praktik'],
            ['kode_ruangan' => 'L203', 'nama_ruangan' => 'Lab Jaringan',    'jenis_ruangan' => 'praktik'],
        ];
        foreach ($ruangans as $data) {
            Ruangan::firstOrCreate(['kode_ruangan' => $data['kode_ruangan']], $data);
        }

        // ─── Periode ──────────────────────────────────────────
        $periodes = [
            [
                'periode'         => 'Ganjil 2025/2026',
                'status'          => 'aktif',
                'tanggal_mulai'   => '2025-09-01',
                'tanggal_selesai' => '2026-01-31',
            ],
            [
                'periode'         => 'Genap 2024/2025',
                'status'          => 'nonaktif',
                'tanggal_mulai'   => '2025-02-01',
                'tanggal_selesai' => '2025-06-30',
            ],
        ];
        foreach ($periodes as $data) {
            Periode::firstOrCreate(['periode' => $data['periode']], $data);
        }
    }
}
