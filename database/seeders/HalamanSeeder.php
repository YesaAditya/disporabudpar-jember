<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HalamanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('halaman_statis')->updateOrInsert(
            ['slug' => 'profil'],
            [
                'judul' => 'Profil',
                'isi' => '<h2>Visi</h2><p>Isi visi dari dokumen resmi.</p><h2>Misi</h2><ol><li>Misi pertama.</li></ol><h2>Tugas dan Fungsi</h2><p>Isi tugas pokok dan fungsi.</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
