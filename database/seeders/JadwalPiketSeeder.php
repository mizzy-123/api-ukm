<?php

namespace Database\Seeders;

use App\Models\JadwalPiket;
use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JadwalPiketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pcc = Organization::find(2);
        $pecc = Organization::find(3);
        $hari = [
            [
                'nama_hari' => 'Senin',
            ],
            [
                'nama_hari' => 'Selasa',
            ],
            [
                'nama_hari' => 'Rabu',
            ],
            [
                'nama_hari' => 'Kamis',
            ],
            [
                'nama_hari' => "Jumat",
            ],
            [
                'nama_hari' => 'Sabtu',
            ],
            [
                'nama_hari' => 'Minggu',
            ],
        ];

        foreach ($hari as $h) {
            JadwalPiket::create([
                'nama_hari' => $h['nama_hari'],
                'organization_id' => $pcc->id
            ]);

            JadwalPiket::create([
                'nama_hari' => $h['nama_hari'],
                'organization_id' => $pecc->id
            ]);
        }
    }
}
