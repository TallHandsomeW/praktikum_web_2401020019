<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('bukus')->insert([
            [
                'kode' => 'BK001',
                'judul' => 'Dasar PHP',
                'kategori_id' => 1,
            ],
            [
                'kode' => 'BK002',
                'judul' => 'Belajar Laravel',
                'kategori_id' => 3,
            ],
        ]);
    }
}