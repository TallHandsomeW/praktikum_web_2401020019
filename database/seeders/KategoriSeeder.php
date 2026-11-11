<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kategoris')->insert([
            ['nama' => 'Pemrograman'],
            ['nama' => 'Basis Data'],
            ['nama' => 'Web'],
        ]);
    }
}