<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KontakSeeder extends Seeder
{
    public function run(): void
    {
        // Data contoh, cek dan sesuaikan lewat admin.
        DB::table('kontak_instansi')->updateOrInsert(
            ['id' => 1],
            [
                'nama_instansi' => 'Dinas Kepemudaan dan Olahraga, Kebudayaan dan Pariwisata Kabupaten Jember',
                'alamat' => 'Jl. MH. Thamrin, Ajung Kulon, Kec. Ajung, Kab. Jember',
                'telepon' => '0331 335 244',
                'jam_layanan' => 'Senin sampai Jumat, 07.30-15.30',
                'updated_at' => now(),
            ]
        );

        // Tautan sosial media dibuat nonaktif sampai URL aslinya diisi.
        foreach (['Instagram', 'YouTube', 'TikTok', 'Facebook'] as $i => $platform) {
            DB::table('media_sosial')->insertOrIgnore([
                'platform' => $platform,
                'url' => 'https://',
                'urutan' => $i + 1,
                'aktif' => false,
            ]);
        }
    }
}
