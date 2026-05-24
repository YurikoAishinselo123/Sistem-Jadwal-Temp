<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('study_programs')->insert([
            ['name' => 'Teknik Informatika', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sistem Informasi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Teknik Komputer', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('courses')->insert([
            ['name' => 'Pemrograman Web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Basis Data', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Jaringan Komputer', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Algoritma & Pemrograman', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kecerdasan Buatan', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('rooms')->insert([
            ['name' => 'Lab Komputer 1', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lab Komputer 2', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ruang 301', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ruang 302', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Aula Utama', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('lecturers')->insert([
            ['name' => 'Dr. Andi Surya, M.T.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Prof. Budi Santoso, Ph.D.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ir. Citra Dewi, M.Sc.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dr. Dian Pratama, M.Kom.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Eko Wibowo, S.T., M.T.', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('assistants')->insert([
            ['name' => 'Fauzan Hidayat', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Gita Rahayu', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hendra Setiawan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Indah Permata', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
