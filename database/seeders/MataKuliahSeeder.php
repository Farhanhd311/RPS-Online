<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MataKuliah;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mataKuliah = [
            // Semester 1
            ['kode_matakuliah' => 'SI001', 'nama_matakuliah' => 'Pengantar Sistem Informasi', 'sks' => 3, 'semester' => 1],
            ['kode_matakuliah' => 'SI002', 'nama_matakuliah' => 'Algoritma dan Pemrograman', 'sks' => 3, 'semester' => 1],
            ['kode_matakuliah' => 'SI003', 'nama_matakuliah' => 'Matematika Diskrit', 'sks' => 3, 'semester' => 1],
            
            // Semester 2
            ['kode_matakuliah' => 'SI101', 'nama_matakuliah' => 'Struktur Data', 'sks' => 3, 'semester' => 2],
            ['kode_matakuliah' => 'SI102', 'nama_matakuliah' => 'Basis Data', 'sks' => 3, 'semester' => 2],
            ['kode_matakuliah' => 'SI103', 'nama_matakuliah' => 'Pemrograman Web', 'sks' => 3, 'semester' => 2],
            
            // Semester 3
            ['kode_matakuliah' => 'SI201', 'nama_matakuliah' => 'Analisis dan Perancangan Sistem', 'sks' => 3, 'semester' => 3],
            ['kode_matakuliah' => 'SI202', 'nama_matakuliah' => 'Pemrograman Berorientasi Objek', 'sks' => 3, 'semester' => 3],
            ['kode_matakuliah' => 'SI203', 'nama_matakuliah' => 'Jaringan Komputer', 'sks' => 3, 'semester' => 3],
            
            // Semester 4
            ['kode_matakuliah' => 'SI301', 'nama_matakuliah' => 'Rekayasa Perangkat Lunak', 'sks' => 3, 'semester' => 4],
            ['kode_matakuliah' => 'SI302', 'nama_matakuliah' => 'Sistem Operasi', 'sks' => 3, 'semester' => 4],
            ['kode_matakuliah' => 'SI303', 'nama_matakuliah' => 'Manajemen Proyek TI', 'sks' => 3, 'semester' => 4],
            
            // Semester 5
            ['kode_matakuliah' => 'SI401', 'nama_matakuliah' => 'Keamanan Sistem Informasi', 'sks' => 3, 'semester' => 5],
            ['kode_matakuliah' => 'SI402', 'nama_matakuliah' => 'Data Mining', 'sks' => 3, 'semester' => 5],
            ['kode_matakuliah' => 'SI403', 'nama_matakuliah' => 'Sistem Informasi Enterprise', 'sks' => 3, 'semester' => 5],
        ];

        foreach ($mataKuliah as $mk) {
            MataKuliah::updateOrCreate(
                ['kode_matakuliah' => $mk['kode_matakuliah']],
                $mk
            );
        }

        $this->command->info('Mata kuliah seeded successfully.');
    }
}
