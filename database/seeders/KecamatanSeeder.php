<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KecamatanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kecamatan')->insertOrIgnore([
            ['nama' => 'Ajung'],
            ['nama' => 'Ambulu'],
            ['nama' => 'Arjasa'],
            ['nama' => 'Balung'],
            ['nama' => 'Bangsalsari'],
            ['nama' => 'Gumukmas'],
            ['nama' => 'Jelbuk'],
            ['nama' => 'Jenggawah'],
            ['nama' => 'Jombang'],
            ['nama' => 'Kalisat'],
            ['nama' => 'Kaliwates'],
            ['nama' => 'Kencong'],
            ['nama' => 'Ledokombo'],
            ['nama' => 'Mayang'],
            ['nama' => 'Mumbulsari'],
            ['nama' => 'Pakusari'],
            ['nama' => 'Panti'],
            ['nama' => 'Patrang'],
            ['nama' => 'Puger'],
            ['nama' => 'Rambipuji'],
            ['nama' => 'Semboro'],
            ['nama' => 'Silo'],
            ['nama' => 'Sukorambi'],
            ['nama' => 'Sukowono'],
            ['nama' => 'Sumberbaru'],
            ['nama' => 'Sumberjambe'],
            ['nama' => 'Sumbersari'],
            ['nama' => 'Tanggul'],
            ['nama' => 'Tempurejo'],
            ['nama' => 'Umbulsari'],
            ['nama' => 'Wuluhan'],
        ]);
    }
}
