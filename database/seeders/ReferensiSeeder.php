<?php

namespace Database\Seeders;

use App\Models\JenisKeluar;
use App\Models\TahunAjaran;
use App\Models\BentukPendidikan;
use Illuminate\Database\Seeder;

class ReferensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TahunAjaran::firstOrCreate(
            [
                'tahun_ajaran_id' => 2026,
            ],
            [
                'nama' => '2026/2027',
                'periode_aktif' => 1,
                'tanggal_mulai' => now()->format('Y-m-d'),
                'tanggal_selesai' => now()->format('Y-m-d'),
            ]
        );
        $data = ['SD', 'SMP', 'SMA'];
        foreach($data as $d){
            BentukPendidikan::firstOrCreate(['nama' => $d]);
        }
        $data = [
            'Lulus',
            'Mutasi',
            'Dikeluarkan (Drop Out)',
            'Mengundurkan Diri',
            'Putus Sekolah',
            'Wafat',
            'Lainnya',
        ];

        foreach ($data as $nama) {
            JenisKeluar::firstOrCreate(['nama' => $nama]);
        }
    }
}
