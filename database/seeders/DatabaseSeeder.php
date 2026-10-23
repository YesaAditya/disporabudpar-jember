<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            KecamatanSeeder::class,
            KategoriSeeder::class,
            BidangSeeder::class,
            HalamanSeeder::class,
            KontakSeeder::class,
        ]);
    }
}
